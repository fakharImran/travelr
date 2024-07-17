
function formatDate(date) {
    // Define an array of month names
    const monthNames = [
        'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
        'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
    ];
    // Get the current month and day
    const currentMonth = monthNames[date.getMonth()];
    const currentDay = String(date.getDate()).padStart(2, '0');

    // Create the formatted string
    var formattedDate = `${currentMonth} ${currentDay}`;
    return formattedDate;
}
function formatDateYMD(date) {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');

    return `${year}-${month}-${day}`;
}
function setTimeWorkCard(table) {
    var sumTotalTime = 0;
    table.rows({ search: 'applied' }).every(function (rowIdx, tableLoop, rowLoop) {
        const data = this.data();
        var timeValue = data[10]; // Assuming column 10 contains the time value like '9hrs, 52mins'
        // Regular expression to extract temphours and tempminutes
        var regex = /(\d+) hrs, (\d+) mins/;

        // Use the regex to extract temphours and tempminutes
        var match = timeValue.match(regex);
        if (match) {
            var hours = parseInt(match[1]);
            var minutes = parseInt(match[2]);

            // Convert hours to minutes and add to the total
            sumTotalTime += hours * 60 + minutes;
        }
        else {
            console.log('no matched');
        }
    });

    if (!isNaN(sumTotalTime)) {
        // Convert the total back to 'hrs, mins' format if needed
        var totalHours = Math.floor(sumTotalTime / 60);
        var totalMinutes = sumTotalTime % 60;
        var totalWorkedTime = totalHours + 'hrs, ' + totalMinutes + 'mins';

        document.getElementById('total_time_worked').innerHTML = totalWorkedTime;
    }
}
// create weekly dates
function convertingData(data, startDate = 0, endDate = 0) {

    // Initialize an array to store the previous 6 weeks
    const previousWeeks = [];

    if (startDate == 0 && endDate == 0) {
        // Calculate the start date of the current week (Sunday)
        const currentWeekStartDate = new Date();
        currentWeekStartDate.setDate(currentWeekStartDate.getDate() - currentWeekStartDate.getDay());


        // Calculate the start and end dates for each of the previous 6 weeks
        for (let i = 0; i < 6; i++) {
            startDate = new Date(currentWeekStartDate);
            startDate.setDate(currentWeekStartDate.getDate() - 7 * i); // Subtract 7 days for each previous week
            endDate = new Date(startDate);
            endDate.setDate(startDate.getDate() + 6); // Add 6 days to get the end of the week
            previousWeeks.push({ startDate, endDate });
        }
    }
    else {
        startDate = new Date(startDate);
        endDate = new Date(endDate);
        // startDate.setDate(startDate.getDate() - startDate.getDay());
        startDate.setDate(startDate.getDate() - 7);
        let currentWeekStartDate = endDate; // Initialize with the provided end date
        currentWeekStartDate.setDate(currentWeekStartDate.getDate() + currentWeekStartDate.getDay());

        // Calculate the difference in milliseconds
        const timeDifference = currentWeekStartDate.getTime() - startDate.getTime();
        // Convert milliseconds to weeks (1 week = 7 days)
        const weeks = Math.floor(timeDifference / (1000 * 60 * 60 * 24 * 7));

        for (let i = 0; i <= weeks; i++) {
            const weekEndDate = new Date(currentWeekStartDate);
            weekEndDate.setDate(currentWeekStartDate.getDate() - 1); // Subtract 1 day to get the week's end date
            const weekStartDate = new Date(weekEndDate);
            weekStartDate.setDate(weekEndDate.getDate() - 6); // Subtract 6 days to get the start date
            // Check if the week's start date is within the provided range
            if (weekStartDate >= startDate) {
                previousWeeks.push({ startDate: weekStartDate, endDate: weekEndDate });
            }

            currentWeekStartDate = weekStartDate; // Set the next week's start date
        }


    }

    //check the weeks arroding to their hours
    var workedHrs = 0;
    var weekarray = [];
    previousWeeks.forEach(week => {
        data.forEach(element => {
            chkDate = element['date'];
            if (formatDateYMD(week.startDate) <= chkDate && formatDateYMD(week.endDate) >= chkDate) {
                workedHrs += element['hours'];
            } else {
            }
        });
        weekarray.push(workedHrs);
        workedHrs = 0;
    });
    previousWeeks.forEach(function (element) {
        element.startDate = formatDate(element.startDate);
        element.endDate = formatDate(element.endDate);
    });
    const previousWeeksArray = [];
    previousWeeks.forEach(function (element) {
        previousWeeksArray.push(element.startDate + ' - ' + element.endDate);
    });

    hoursWorked = weekarray.reverse();
    labels = previousWeeksArray.reverse();
}
convertingData(chartData);

const data = {
    labels: labels,
    datasets: [{
        label: 'Total Time Worked',
        backgroundColor: '#1892C0',
        borderColor: 'rgb(255, 99, 132)',
        data: hoursWorked,
    }]
};

const config = {
    type: 'bar',
    data: data,
    options: {
        // responsive: true,
        // maintainAspectRatio: false,
        scales: {
            yAxes: [{
                ticks: {
                    userCallback: function (v) {
                        return epoch_to_hh_mm_ss(v)
                    },
                    stepSize: 60 * 60,
                    beginAtZero: true
                }
            }]
        },
        tooltips: {
            callbacks: {
                label: function (tooltipItem, data) {
                    return data.datasets[tooltipItem.datasetIndex].label + ': ' + epoch_to_hh_mm_ss(tooltipItem.yLabel)
                }
            }
        }
    }
};

const epoch_to_hh_mm_ss = epoch => {
    const hours = Math.floor(epoch / 3600);
    const minutes = Math.floor((epoch % 3600) / 60);
    const seconds = epoch % 60;

    const formattedHours = hours.toString().padStart(2, '0');
    const formattedMinutes = minutes.toString().padStart(2, '0');
    const formattedSeconds = seconds.toString().padStart(2, '0');

    return `${formattedHours}:${formattedMinutes}`;
    // return `${formattedHours}:${formattedMinutes}:${formattedSeconds}`;
};

var myChartJS = new Chart(
    document.getElementById('myChart'),
    config
);


// datatable

//function for change the graph it is comming from datatable search filters 
function changeGraph(table) {
    var filteredIndexes = table.rows({ search: 'applied' }).indexes();
    var filteredData = [];
    filteredIndexes.each(function (index) {
        var rowData = table.row(index).data();
        filteredData.push(rowData);
    });
    var colData = [];
    filteredData.forEach(element => {
        const dateTime = element[8].split(' '); // element[6] is date and time ex: 12-09-2023 7:50 PM
        const currentDate1 = new Date(dateTime[0]); // dateTime is only date ex: 12-09-2023

        var inputString = element[10];
        var regex = /(\d+).*?(\d+)/; // Regular expression to match the first integers before and after the comma
        var match = inputString.match(regex);
        if (match) {
            var beforeComma = match[1]; // The first set of integers before the comma
            var afterComma = match[2]; // The first set of integers after the comma
            var Hours = (beforeComma * 1) + (afterComma / 60);
            var seconds = beforeComma * 60 * 60 + afterComma * 60;
        } else {
            console.log('No match found.');
        }
        colData.push({ 'date': formatDateYMD(currentDate1), 'hours': seconds });


    });
    return colData;
}


$(document).ready(function () {
    var table = $('#mechandiserDatatable').DataTable({
        // Add your custom options here
        scrollX: true, // scroll horizontally
        paging: true, // Enable pagination
        searching: true, // Enable search bar
        ordering: true, // Enable column sorting
        lengthChange: false, // Show a dropdown for changing the number of records shown per page
        pageLength: 10, // Set the default number of records shown per page to 10
        dom: 'lBfrtip', // Define the layout of DataTable elements (optional)
        buttons: ['copy', 'excel', 'pdf', 'print'], // Add some custom buttons (optional)
        "pagingType": "full_numbers"
    });
    // Custom search input for 'Name' column
    $('#store-search').on('change', function () {

        // Perform the search on the first column of the DataTable
        const searchValue = this.value.trim();
        table.column(0).search(searchValue ? `^${searchValue}$` : '', true, false).draw();
        // table.column(0).search(this.value).draw();
        var storeName = this.value;

        // Assuming you have a dropdown with ID 'location-search'
        var dropdown = $('#location-search');

        allStores.forEach(function (store) {
            if (storeName == store[0]) {
                // Append each option into the select list
                // Append the column data to the dropdown
                table.column(1).search('', true, false).draw(); // Clear previous search
                dropdown.empty();
                dropdown.append('<option value="" selected>--Select--</option>');
                var storeLocations = store[1];
                storeLocations.forEach(function (storeLocation) {
                    dropdown.append('<option value="' + storeLocation + '">' + storeLocation + '</option>');
                });
            }
        });
        if (storeName == "") {
            // table.lengthMenu= [ [5, 10, 25, 50, -1], [5, 10, 25, 50, "All"] ];
            table.column(1).search('', true, false).draw(); // Clear previous search
            dropdown.empty();
            dropdown.append('<option value="" selected>--Select--</option>');
            // allUniqueLocations.forEach(function (location) {
            //     // Append each option into the select list
            //     // Append the column data to the dropdown
            //     dropdown.innerHTML = '<option value="" selected>--Select--</option>';
            //     dropdown.append('<option value="' + location + '">' + location + '</option>');
            // });
        }
        setTimeWorkCard(table);


        var convertedToChartData = changeGraph(table);
        convertingData(convertedToChartData, startDate, endDate);
        myChartJS.data.labels = labels;
        myChartJS.data.datasets[0].data = hoursWorked;
        myChartJS.update();

    });

    $('#location-search').on('change', function () {
        const searchValue = this.value.trim();
        table.column(1).search(searchValue ? `^${searchValue}$` : '', true, false).draw();
        // table.column(1).search(this.value).draw();
        var convertedToChartData = changeGraph(table);

        setTimeWorkCard(table);


        convertingData(convertedToChartData, startDate, endDate);
        myChartJS.data.labels = labels;
        myChartJS.data.datasets[0].data = hoursWorked;
        myChartJS.update();
    });
    $('#merchandiser-search').on('change', function () {
        const searchValue = this.value.trim();
        table.column(11).search(searchValue ? `^${searchValue}$` : '', true, false).draw();
        // table.column(11).search(this.value).draw();
        setTimeWorkCard(table);

        var convertedToChartData = changeGraph(table);
        convertingData(convertedToChartData, startDate, endDate);
        myChartJS.data.labels = labels;
        myChartJS.data.datasets[0].data = hoursWorked;
        myChartJS.update();

    });
    $('#period-search').on('change', function () {

        if (this.value.includes('to')) {
            const parts = this.value.split('to');

            var start = parts[0].trim(); // Remove leading/trailing spaces
            startDate = start.replace(/^\s+/, ''); // Remove the first space
            startDate = new Date(startDate);
            startDate = (startDate);

            var end = parts[1].trim(); // Remove leading/trailing spaces
            endDate = end.replace(/^\s+/, ''); // Remove the first space
            endDate = new Date(endDate);
            endDate = (endDate);

            table.column(8).search('', true, false).draw(); // Clear previous search

            var searchTerms = []; // Initialize an array to store search terms
            function dateRange(startDate, endDate) {
                var currentDate = new Date(startDate);
                var endDateObj = new Date(endDate);
                var dates = [];

                while (currentDate <= endDateObj) {
                    dates.push(formatDateYMD(new Date(currentDate)));
                    currentDate.setDate(currentDate.getDate() + 1);
                }
                return dates;
            }
            var dateList = dateRange(startDate, endDate);
            table.column(8).search(dateList.join('|'), true, false, true).draw(); // Join and apply search terms
            var convertedToChartData = changeGraph(table);

            setTimeWorkCard(table);

            convertingData(convertedToChartData, startDate, endDate);
            myChartJS.data.labels = labels;
            myChartJS.data.datasets[0].data = hoursWorked;
            myChartJS.update();
        } else {
            startDate = new Date(this.value);
            endDate = startDate;

            table.column(8).search('', true, false).draw(); // Clear previous search

            var searchTerms = []; // Initialize an array to store search terms
            function dateRange(startDate, endDate) {
                var currentDate = new Date(startDate);
                var endDateObj = new Date(endDate);
                var dates = [];

                while (currentDate <= endDateObj) {
                    dates.push(formatDateYMD(new Date(currentDate)));
                    currentDate.setDate(currentDate.getDate() + 1);
                }
                return dates;
            }
            var dateList = dateRange(startDate, endDate);
            table.column(8).search(dateList.join('|'), true, false, true).draw(); // Join and apply search terms
            var convertedToChartData = changeGraph(table);

            setTimeWorkCard(table);

            convertingData(convertedToChartData, startDate, endDate);
            myChartJS.data.labels = labels;
            myChartJS.data.datasets[0].data = hoursWorked;
            myChartJS.update();
        }

    });

    document.getElementById('clearDate').addEventListener('click', function (element) {
        table.column(8).search('', true, false).draw(); // Clear previous search
        document.getElementById('period-search').clear;
        endDate = 0;
        startDate = 0;
        // table.column(8).search('').draw();
        var convertedToChartData = changeGraph(table);
        convertingData(convertedToChartData);
        myChartJS.data.labels = labels;
        myChartJS.data.datasets[0].data = hoursWorked;
        myChartJS.update();
        document.getElementById('period-search').value = 'Date Range';

    });
});