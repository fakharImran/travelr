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


// Assuming you have already loaded the `allStores` and `outOfStockData` variables

// function setCards() {
//     const todayDate = new Date();
//     const formattedDate = todayDate.toISOString().split('T')[0];

//     // Calculate the total number of unique stores
//     const totalStores = allStores.reduce((count, store) => {
//         if (!count.includes(store.name_of_store)) {
//             count.push(store.name_of_store);
//         }
//         return count;
//     }, []).length;

//     // Calculate the number of unique stores in the outOfStockData
//     const uniqueStores = outOfStockData.reduce((count, data) => {
//         if (!count.includes(data.store_id)) {
//             count.push(data.store_id);
//         }
//         return count;
//     }, []);

//     const uniqueStoreCount = uniqueStores.length;

//     console.log('in set card',totalStores,  uniqueStoreCount);

//     // Update the HTML content
//     const dateSet = document.getElementById('date_set');
//     const storesOutOfStock = document.getElementById('stores_out_of_stock');

//     if (dateSet) {
//         dateSet.textContent = formattedDate;
//     }

//     if (storesOutOfStock) {
//         storesOutOfStock.innerHTML = `<span style="color: #CA371B">${uniqueStoreCount}</span> / ${totalStores}`;
//     }
// }

function setCards(table, startDate = 0, endDate = 0) {
    var sumClosingweekStock = 0;
    var sumOpeningWeekStock = 0;
    var sumUnits = 0;
    var sumCases = 0;


    const storeServised = new Set(); // Use a Set to store unique store names
    const stores_out_of_stock = new Set(); // Use a Set to store unique store names
    const products_out_of_stock = new Set(); // Use a Set to store unique store names
    const stores_with_exp_products = new Set(); // Use a Set to store unique store names

    // Use a Set to keep track of unique stores
    // Iterate over the visible rows and calculate the minimum and maximum product prices
    table.rows({ search: 'applied' }).every(function (rowIdx, tableLoop, rowLoop) {
        const data = this.data();
        var units = parseInt((data[6] == '') ? '0' : data[6]); // Assuming column 1 contains the store
        sumUnits += units;
        // console.log('untis ', units);
        var cases = parseInt((data[7] == '') ? '0' : data[7]); // Assuming column 1 contains the store
        sumCases += cases;
        const tempStoreServised = data[12];

        // created_at = data[0]?new Date(data[0]):null;
        // created_at_date = created_at?formatDateYMD(created_at):null;
        // date_now = formatDateYMD(new Date());
        // if(created_at_date == date_now){
        //     console.log(created_at?formatDateYMD(created_at):null, formatDateYMD(new Date()));
        //     const tempStoreServised = data[11];
        // }

        if (tempStoreServised.trim() !== "") {
            storeServised.add(tempStoreServised); // Add the store name to the Set if it's not an empty string
        }

        const tempStoreOutOfStock = data[8];

        if (tempStoreOutOfStock.trim() !== "") {
            products_out_of_stock.add(tempStoreOutOfStock); // Add the store name to the Set if it's not an empty string
        }

        const tempProductOutOfStock = data[9];

        if (tempProductOutOfStock.trim() !== "") {
            stores_out_of_stock.add(tempProductOutOfStock); // Add the store name to the Set if it's not an empty string
        }

        const tempStoreExpProduct = data[10];

        if (tempStoreExpProduct.trim() !== "") {
            stores_with_exp_products.add(tempStoreExpProduct); // Add the store name to the Set if it's not an empty string
        }


        var stockDate = data[0];
        const sevenDaysAgo = new Date();
        sevenDaysAgo.setDate(sevenDaysAgo.getDate() - 7);
        const sevenDaysAgoString = sevenDaysAgo.toISOString().split('T')[0];
        if (stockDate <= sevenDaysAgoString) {
            sumOpeningWeekStock += units + cases;
        }
        var tempTotal = units + cases;
        // console.log(units,'units',cases, 'cases' );
        sumClosingweekStock += tempTotal;

    });


    const numberOfStoreServised = storeServised.size;
    const numberStoreOfOutOfStock = stores_out_of_stock.size;
    const numberOfProductOutOfStock = products_out_of_stock.size;
    const numberOfStoreExpProduct = stores_with_exp_products.size;

    // console.log('Number of service stores: ', numberOfStoreServised);
    // console.log('Number of store out of stock:', numberStoreOfOutOfStock);
    // console.log('Number of product out of stock:', numberOfProductOutOfStock);
    // console.log('Number of store exp product:', numberOfStoreExpProduct);

    // console.log('startDate', formatDateYMD(startDate), 'enddate', formatDateYMD(endDate));

    document.getElementById('total_stock_count').innerHTML = sumCases;
    document.getElementById('total_stock_count_cases').innerHTML = sumUnits;

    if(numberOfStoreServised==allUniqueLocations.length)
    {
        document.getElementById('serviced_stores').innerHTML =  numberOfStoreServised + ' / ' + allUniqueLocations.length;
    }
    else
    {
        document.getElementById('serviced_stores').innerHTML = '<span style="color: #CA371B">' + numberOfStoreServised + ' /</span> ' + allUniqueLocations.length;
    }
    if(numberStoreOfOutOfStock==0)
    {
        document.getElementById('stores_out_of_stock').innerHTML =  numberStoreOfOutOfStock + ' / ' + allUniqueLocations.length;
    }
    else
    {
        document.getElementById('stores_out_of_stock').innerHTML = '<span style="color: #CA371B">' + numberStoreOfOutOfStock + ' /</span> ' + allUniqueLocations.length;
    }

    if(numberOfProductOutOfStock==0)
    {
        document.getElementById('products_out_of_stock').innerHTML = numberOfProductOutOfStock + ' / ' + products.length;
    }
    else
    {
        document.getElementById('products_out_of_stock').innerHTML = '<span style="color: #CA371B">' + numberOfProductOutOfStock + ' /</span> ' + products.length;
    }
   
    if(numberOfStoreExpProduct==0)
    {
        document.getElementById('stores_with_exp_products').innerHTML = numberOfStoreExpProduct + ' / ' + allUniqueLocations.length;
    }
    else
    {
        document.getElementById('stores_with_exp_products').innerHTML = '<span style="color: #CA371B">' + numberOfStoreExpProduct + ' /</span> ' + allUniqueLocations.length;
    }

    if (startDate != 0 && endDate != 0) {
        const dateRangeElements = document.getElementsByClassName('date_range_set');

        for (const element of dateRangeElements) {
            // element.innerHTML = formatDateYMD(startDate) + ' to ' + formatDateYMD(endDate);
        }
              if(uniqueNumberOfStoreServicedCount == allUniqueLocations.length)
                {
                    document.getElementById('serviced_stores').innerHTML =  uniqueNumberOfStoreServicedCount + ' / ' + allUniqueLocations.length;
                }
                else
                {
                    document.getElementById('serviced_stores').innerHTML = '<span style="color: #CA371B">' + uniqueNumberOfStoreServicedCount + ' /</span> ' + allUniqueLocations.length;
                }   
    }

}

// Call the setCards function to update the card's content




//create last days dates
function createLastDaysDates(data, startDate = 0, endDate = 0) {
    // Initialize an array to store the previous 7 days
    const previousSevenDays = [];

    if (startDate == 0 && endDate == 0) {
        // Calculate the end date (today)
        endDate = new Date();

        // Calculate the start date (7 days ago from today)
        startDate = new Date();
        startDate.setDate(endDate.getDate() - 6);
    } else {
        // Parse provided start and end dates
        startDate = new Date(startDate);
        endDate = new Date(endDate);
    }

    // Iterate for each day in the last 7 days
    for (let i = 0; i < 7; i++) {
        const currentDate = new Date(startDate);
        currentDate.setDate(startDate.getDate() + i);

        // Calculate the start and end times for the current day
        const dayStart = new Date(currentDate);
        dayStart.setHours(0, 0, 0, 0);
        const dayEnd = new Date(currentDate);
        dayEnd.setHours(23, 59, 59, 999);

        // Filter data for the current day
        const filteredData = data.filter(element => {
            const elementDate = new Date(element['date']);
            return elementDate >= dayStart && elementDate <= dayEnd;
        });
        let totalStock;
        // Calculate the total stock for the current day
        if (graphUnit == "Unit") {
            totalStock = filteredData.reduce((acc, element) => acc + parseInt(element['stock']), 0);
        }
         else if(graphUnit=='UnitAndCase')
        {
            // let tempStock= parseInt(element['stockCases']) + parseInt(element['stock']);
            totalStock = filteredData.reduce((acc, element) => acc + parseInt(element['sumUnitCase']), 0);

            console.log('totalStock',totalStock);
        }
        else {
            totalStock = filteredData.reduce((acc, element) => acc + parseInt(element['stockCases']), 0);
        }
        previousSevenDays.push(totalStock);
    }

    // Format dates and reverse the arrays
    const formattedDates = previousSevenDays.map((_, i) => {
        const currentDate = new Date(startDate);
        currentDate.setDate(startDate.getDate() + i);
        return formatDate(currentDate);
    });

    labels = formattedDates;
    periodData = previousSevenDays;
}
//create last week dates
function createLastWeeksDates(data, startDate = 0, endDate = 0) {

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
    var totalStock = 0;
    var weekarray = [];
    previousWeeks.forEach(week => {
        data.forEach(element => {
            chkDate = element['date'];
            if (formatDateYMD(week.startDate) <= (chkDate) && formatDateYMD(week.endDate) >= (chkDate)) {
                if (graphUnit == "Unit") {
                    totalStock += parseInt(element['stock']);
                }
                else if(graphUnit=='UnitAndCase')
                {
                    totalStock += parseInt(element['sumUnitCase']);
                    console.log('totalStock>>>>>>.',totalStock);
                }   
                else {
                    totalStock += parseInt(element['stockCases']);
                }
            } else {
            }
        });
        weekarray.push(totalStock);
        totalStock = 0;
    });
    previousWeeks.forEach(function (element) {
        element.startDate = formatDate(element.startDate);
        element.endDate = formatDate(element.endDate);
    });
    const previousWeeksArray = [];
    previousWeeks.forEach(function (element) {
        previousWeeksArray.push(element.startDate + ' - ' + element.endDate);
    });

    periodData = weekarray.reverse();
    labels = previousWeeksArray.reverse();
}
//create last months dates
function createLastMonthsDates(data, startDate = 0, endDate = 0) {
    // Initialize an array to store the previous 7 months
    const previousMonths = [];

    if (startDate == 0 && endDate == 0) {
        // Calculate the start date of the current month
        const currentMonthStartDate = new Date();
        currentMonthStartDate.setDate(1); // Set the date to the first day of the month

        // Calculate the start and end dates for each of the previous 7 months
        for (let i = 0; i < 7; i++) {
            startDate = new Date(currentMonthStartDate);
            startDate.setMonth(currentMonthStartDate.getMonth() - i); // Subtract i months for each previous month
            startDate.setDate(1); // Set the date to the first day of the month

            endDate = new Date(startDate);
            endDate.setMonth(startDate.getMonth() + 1); // Add 1 month to get the end of the month
            endDate.setDate(0); // Set the date to the last day of the month

            previousMonths.push({ startDate, endDate });
        }
    }
    else {
        startDate = new Date(startDate);
        endDate = new Date(endDate);
        startDate.setDate(1); // Set the date to the first day of the month

        let currentMonthStartDate = endDate; // Initialize with the provided end date
        currentMonthStartDate.setDate(1); // Set the date to the first day of the month

        // Calculate the difference in months
        const monthsDifference = (currentMonthStartDate.getFullYear() - startDate.getFullYear()) * 12
            + currentMonthStartDate.getMonth() - startDate.getMonth();

        for (let i = 0; i <= monthsDifference; i++) {
            const monthEndDate = new Date(currentMonthStartDate);
            monthEndDate.setMonth(currentMonthStartDate.getMonth() + 1); // Add 1 month to get the end date
            monthEndDate.setDate(0); // Set the date to the last day of the month

            const monthStartDate = new Date(monthEndDate);
            monthStartDate.setMonth(monthEndDate.getMonth() - 1); // Subtract 1 month to get the start date
            monthStartDate.setDate(1); // Set the date to the first day of the month

            // Check if the month's start date is within the provided range
            if (monthStartDate >= startDate) {
                previousMonths.push({ startDate: monthStartDate, endDate: monthEndDate });
            }

            currentMonthStartDate = monthStartDate; // Set the next month's start date
        }
    }

    // Calculate the data for each month
    const monthArray = [];
    previousMonths.forEach(month => {
        let totalStock = 0;
        data.forEach(element => {
            const chkDate = new Date(element.date);
            if (formatDateYMD(month.startDate) <= formatDateYMD(chkDate) && formatDateYMD(month.endDate) >= formatDateYMD(chkDate)) {
                if (graphUnit == "Unit") {
                    totalStock += parseInt(element.stock);
                }
                else if(graphUnit=='UnitAndCase')
                {
                    totalStock += parseInt(element['sumUnitCase']);
                }  
                else {
                    totalStock += parseInt(element.stockCases);
                }
            }
        });
        monthArray.push(totalStock);
    });

    // Format the labels for each month
    const previousMonthsArray = previousMonths.map(month => {
        const formattedStartDate = formatDate(month.startDate);
        const formattedEndDate = formatDate(month.endDate);
        return `${formattedStartDate} - ${formattedEndDate}`;
    });

    // Reverse the arrays for proper display order
    periodData = monthArray.reverse();
    labels = previousMonthsArray.reverse();
}

createLastWeeksDates(convertedToChartData);

function changePeriod(e) {
    switch (e.value) {
        case 'Daily':

            createLastDaysDates(convertedToChartData);
            myChartJS.data.labels = labels;
            myChartJS.data.datasets[0].data = periodData;
            myChartJS.update();
            graphFormat = 'days';
            break;
        case 'Weekly':
            createLastWeeksDates(convertedToChartData);
            myChartJS.data.labels = labels;
            myChartJS.data.datasets[0].data = periodData;
            myChartJS.update();
            graphFormat = 'weeks';
            break;
        case 'Monthly':
            createLastMonthsDates(convertedToChartData);
            myChartJS.data.labels = labels;
            myChartJS.data.datasets[0].data = periodData;
            myChartJS.update();
            graphFormat = 'months';
            break;
        default:
            createLastWeeksDates(convertedToChartData);
            myChartJS.data.labels = labels;
            myChartJS.data.datasets[0].data = periodData;
            myChartJS.update();
            graphFormat = 'weeks';
            break;
    }
}

function changeUnitCount(e) {
    switch (e.value) {
        case 'Unit':
            graphUnit = 'Unit';

            break;
        case 'Case':
            graphUnit = 'Case';
            break;
        case 'UnitAndCase':
            graphUnit = 'UnitAndCase';
            break;
        default:
            graphUnit = 'Unit';
            break;
    }
    switch (graphFormat) {
        case 'days':
            changePeriod({ 'value': "Daily" });
            break;
        case 'weeks':
            changePeriod({ 'value': 'Weekly' });
            break;
        case 'months':
            changePeriod({ 'value': 'Monthly' });
            break;
        default:
            break;
    }
}

const data = {
    labels: labels,
    datasets: [{
        label: 'Stock Count',
        backgroundColor: '#1892C0',
        borderColor: 'rgb(255, 99, 132)',
        // data: periodData,
        data: periodData,
    }]
};

const config = {
    type: 'bar',
    data: data,
    options: {
        // responsive: true,
        // maintainAspectRatio: false,
        scales: {
            // y: {
            //     type: 'time',
            //     time: {
            //         unit: 'month'
            //     }
            // },
            yAxes: [{
                scaleLabel: {
                    display: true,
                    labelString: 'Total Stocks'
                },
                ticks: {
                    stepSize: 10,
                    beginAtZero: true
                }
            }]
        },
        tooltips: {
            callbacks: {
                label: function (tooltipItem, data) {
                    return data.datasets[tooltipItem.datasetIndex].label + ': ' + tooltipItem.yLabel
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

    return `${formattedHours}:${formattedMinutes}:${formattedSeconds}`;
};

var myChartJS = new Chart(
    document.getElementById('myChart'),
    config
);


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
        const dateTime = element[0]; // element[0] is date and time ex: 12-09-2023 7:50 PM
        const currentDate2 = new Date(dateTime); // dateTime is only date ex: 12-09-2023 
        const currentDate1 = currentDate2.toISOString();
        // console.log('element[0]', element[0], 'currentDate1', currentDate1);
        var stockcase = parseInt( (element[7] == '')?0:element[7]);
        var stockunits = parseInt((element[6] == '')?0:element[6]);

        var sumUnitCase=parseInt((element[13] == '')?0:element[13]);
        colData.push({ 'date': currentDate1, 'stock': stockunits, 'stockCases': stockcase, 'sumUnitCase':sumUnitCase });

        // colData.push({ 'date': currentDate1, 'stock': stockunits, 'stockCases': stockcase });
    });
    console.log('col data is ->>>>>>>>>>>>>>>>.', colData);
    return colData;
}


$(document).ready(function () {
    var table = $('#businessSummaryDatatable').DataTable({
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
    setCards(table); 

    if(uniqueNumberOfStoreServicedCount == allUniqueLocations.length)
    {
        document.getElementById('serviced_stores').innerHTML =  uniqueNumberOfStoreServicedCount + ' / ' + allUniqueLocations.length;
    }
    else
    {
        document.getElementById('serviced_stores').innerHTML = '<span style="color: #CA371B">' + uniqueNumberOfStoreServicedCount + ' /</span> ' + allUniqueLocations.length;
    }

    // Custom search input for 'Name' column
    $('#store-search').on('change', function () {

        // Perform the search on the first column of the DataTable
        const searchValue = this.value.trim();
        table.column(1).search(searchValue ? `^${searchValue}$` : '', true, false).draw();
        // table.column(0).search(this.value).draw();
        if (this.value == '') {

            setCards(table, new Date(), new Date());
        }
        else{
            setCards(table);
        }


        var storeName = this.value;

        // Assuming you have a dropdown with ID 'location-search'
        var dropdown = $('#location-search');

        allStores.forEach(function (store) {
            if (storeName == store[0]) {
                // Append each option into the select list
                // Append the column data to the dropdown
                table.column(2).search('', true, false).draw(); // Clear previous search
                dropdown.empty();
                dropdown.append('<option value="" selected>--Select--</option>');
                var storeLocations = store[1];
                // console.log(storeLocations, 'aaaa');
                storeLocations.forEach(function (storeLocation) {
                    dropdown.append('<option value="' + storeLocation + '">' + storeLocation + '</option>');
                });
            }
        });
        if (storeName == "") {
            // table.lengthMenu= [ [5, 10, 25, 50, -1], [5, 10, 25, 50, "All"] ];
            table.column(2).search('', true, false).draw(); // Clear previous search
            dropdown.empty();
            dropdown.append('<option value="" selected>--Select--</option>');

        }
        // Empty the dropdown to remove previous options
        convertedToChartData = changeGraph(table);
        switch (graphFormat) {
            case 'days':
                createLastDaysDates(convertedToChartData);
                break;
            case 'weeks':
                createLastWeeksDates(convertedToChartData);
                break;
            case 'months':
                createLastMonthsDates(convertedToChartData);
                break;
            default:
                createLastWeeksDates(convertedToChartData);
                break;
        }
        myChartJS.data.labels = labels;
        myChartJS.data.datasets[0].data = periodData;
        myChartJS.update();

    });

    $('#location-search').on('change', function () {
        const searchValue = this.value.trim();
        table.column(2).search(searchValue ? `^${searchValue}$` : '', true, false).draw();
        // table.column(1).search(this.value).draw();
        setCards(table);

        convertedToChartData = changeGraph(table);

        switch (graphFormat) {
            case 'days':
                createLastDaysDates(convertedToChartData);
                break;
            case 'weeks':
                createLastWeeksDates(convertedToChartData);
                break;
            case 'months':
                createLastMonthsDates(convertedToChartData);
                break;
            default:
                createLastWeeksDates(convertedToChartData);
                break;
        }
        myChartJS.data.labels = labels;
        myChartJS.data.datasets[0].data = periodData;
        myChartJS.update();
    });

    $('#category-search').on('change', function () {
        const searchValue = this.value.trim();
        table.column(3).search(searchValue ? `^${searchValue}$` : '', true, false).draw();
        if (this.value == '') {
            setCards(table, new Date(), new Date());
        }
        else{
            setCards(table);
        }

        convertedToChartData = changeGraph(table);

        switch (graphFormat) {
            case 'days':
                createLastDaysDates(convertedToChartData);
                break;
            case 'weeks':
                createLastWeeksDates(convertedToChartData);
                break;
            case 'months':
                createLastMonthsDates(convertedToChartData);
                break;
            default:
                createLastWeeksDates(convertedToChartData);
                break;
        }
        myChartJS.data.labels = labels;
        myChartJS.data.datasets[0].data = periodData;
        myChartJS.update();
    });
    

    $('#product-search').on('change', function () {
        const searchValue = this.value.trim();
        table.column(4).search(searchValue ? `^${searchValue}$` : '', true, false).draw();
        if (this.value == '') {
            setCards(table, new Date(), new Date());
        }
        else{
            setCards(table);
        }

        // console.log("search product", searchValue);
        convertedToChartData = changeGraph(table);

        switch (graphFormat) {
            case 'days':
                createLastDaysDates(convertedToChartData);
                break;
            case 'weeks':
                createLastWeeksDates(convertedToChartData);
                break;
            case 'months':
                createLastMonthsDates(convertedToChartData);
                break;
            default:
                createLastWeeksDates(convertedToChartData);
                break;
        }
        myChartJS.data.labels = labels;
        myChartJS.data.datasets[0].data = periodData;
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

            table.column(0).search('', true, false).draw(); // Clear previous search
            setCards(table, startDate, endDate);

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
            table.column(0).search(dateList.join('|'), true, false, true).draw(); // Join and apply search terms
            setCards(table, startDate, endDate);

            convertedToChartData = changeGraph(table);
            switch (graphFormat) {
                case 'days':
                    createLastDaysDates(convertedToChartData, startDate, endDate);
                    break;
                case 'weeks':
                    createLastWeeksDates(convertedToChartData, startDate, endDate);
                    break;
                case 'months':
                    createLastMonthsDates(convertedToChartData, startDate, endDate);
                    break;
                default:
                    createLastWeeksDates(convertedToChartData, startDate, endDate);
                    break;
            }
            myChartJS.data.labels = labels;
            myChartJS.data.datasets[0].data = periodData;
            myChartJS.update();
        } else {
            
            startDate = new Date(this.value);
            endDate = startDate;

            table.column(0).search('', true, false).draw(); // Clear previous search
            setCards(table, startDate, endDate);

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
            table.column(0).search(dateList.join('|'), true, false, true).draw(); // Join and apply search terms
            setCards(table, startDate, endDate);

            convertedToChartData = changeGraph(table);
            switch (graphFormat) {
                case 'days':
                    createLastDaysDates(convertedToChartData, startDate, endDate);
                    break;
                case 'weeks':
                    createLastWeeksDates(convertedToChartData, startDate, endDate);
                    break;
                case 'months':
                    createLastMonthsDates(convertedToChartData, startDate, endDate);
                    break;
                default:
                    createLastWeeksDates(convertedToChartData, startDate, endDate);
                    break;
            }
            myChartJS.data.labels = labels;
            myChartJS.data.datasets[0].data = periodData;
            myChartJS.update();
        }

    });

  
});