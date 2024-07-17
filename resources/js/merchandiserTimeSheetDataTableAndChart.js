
function formatDate(date) 
{
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
// console.log('----------------------------------------');
// create weekly dates
function convertingData(data, startDate=0, endDate=0) {

    // console.log(data, 'dataaaaaaaaa');

    console.log(startDate, endDate, 'start and end date');
    // Initialize an array to store the previous 6 weeks
    const previousWeeks = [];

    if(startDate==0 && endDate==0)   
    { 
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
    else
    {
        startDate = new Date(startDate);
        endDate = new Date(endDate);
        // startDate.setDate(startDate.getDate() - startDate.getDay());
        startDate.setDate(startDate.getDate() - 7);
        console.log(startDate, 'startDate');
        let currentWeekStartDate = endDate; // Initialize with the provided end date
        currentWeekStartDate.setDate(currentWeekStartDate.getDate() + currentWeekStartDate.getDay());

        // Calculate the difference in milliseconds
        const timeDifference = currentWeekStartDate.getTime() - startDate.getTime();
        // Convert milliseconds to weeks (1 week = 7 days)
        const weeks = Math.floor(timeDifference / (1000 * 60 * 60 * 24 * 7));

        console.log(weeks, 'weeks');

        for (let i = 0; i <= weeks; i++) {
          const weekEndDate = new Date(currentWeekStartDate);
          weekEndDate.setDate(currentWeekStartDate.getDate() - 1); // Subtract 1 day to get the week's end date
          const weekStartDate = new Date(weekEndDate);
          weekStartDate.setDate(weekEndDate.getDate() - 6); // Subtract 6 days to get the start date
      
          console.log(weekStartDate >= startDate, startDate, weekStartDate, 'loop if check');
          // Check if the week's start date is within the provided range
          if (weekStartDate >= startDate) {
            previousWeeks.push({ startDate: weekStartDate, endDate: weekEndDate });
          }
      
          currentWeekStartDate = weekStartDate; // Set the next week's start date
        }
      
        
    }

    console.log(previousWeeks, "chkinngng");
    // console.log('**************************************************');
    //check the weeks arroding to their hours
    var workedHrs=0;
    var weekarray=[];
    previousWeeks.forEach(week =>{
        data.forEach(element => {
            chkDate=element['date'];
            if(formatDateYMD(week.startDate)<=chkDate && formatDateYMD(week.endDate)>chkDate)
            {
                workedHrs+= element['hours'] ;
                // console.log("date for check is "+ chkDate+ ", now hours " +workedHrs);
            }else{
                // console.log("date for check is "+ chkDate+ " <br> start date "+ formatDateYMD(week.startDate)+ "<br> end date "+ formatDateYMD(week.endDate));
            }
        });
        // console.log('working hours', workedHrs);
    weekarray.push(workedHrs);
    workedHrs =0 ;
    });
    // console.log('**************************************************');

    previousWeeks.forEach(function(element) {
    element.startDate = formatDate(element.startDate);
    element.endDate = formatDate(element.endDate);
    });
    // console.log('Previous 6 Weeks:', previousWeeks);
    const previousWeeksArray = [];
    previousWeeks.forEach(function(element) {
    //   console.log(element.startDate);
    previousWeeksArray.push(element.startDate + ' - ' + element.endDate);
    });
    console.log(previousWeeksArray);

    hoursWorked = weekarray.reverse();
    labels = previousWeeksArray.reverse();
}
convertingData(chartData);

const data = {
    labels: labels,
    datasets: [{
        label: 'Total Hours Worked',
        backgroundColor: '#1892C0',
        borderColor: 'rgb(255, 99, 132)',
        data: hoursWorked,
    }]
};
const config = {
    type: 'bar',
    data: data,
    options: {
    scales: {
        y: {
        beginAtZero: true
        }
    }
    },
};

var myChartJS= new Chart(
    document.getElementById('myChart'),
    config
);



// datatable

    //function for change the graph it is comming from datatable search filters 
    function changeGraph(table){
      var filteredIndexes = table.rows({ search: 'applied' }).indexes();
      var filteredData = [];
      filteredIndexes.each(function(index) {
          var rowData = table.row(index).data();
          filteredData.push(rowData);
      });
      var colData = [];
      filteredData.forEach(element => {
          const dateTime = element[6].split(' '); // element[6] is date and time ex: 12-09-2023 7:50 PM
          const currentDate1 = new Date(dateTime[0]); // dateTime is only date ex: 12-09-2023

          var inputString = element[10];
        //   console.log(inputString, "input stringgggg");
          var regex = /(\d+).*?(\d+)/; // Regular expression to match the first integers before and after the comma
          var match = inputString.match(regex);
          if (match) {
              var beforeComma = match[1]; // The first set of integers before the comma
              var afterComma = match[2]; // The first set of integers after the comma
              var Hours = (beforeComma*1) + (afterComma/60);
            //   console.log(Hours, 'in change grph hoursss and match is ', match );
          } else {
              console.log('No match found.');
          }
          colData.push({'date':formatDateYMD(currentDate1),'hours':Hours});
      });
      return colData;
  }


  $(document).ready(function() {
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
      $('#store-search').on('change', function() {

          // Perform the search on the first column of the DataTable
          table.column(0).search(this.value).draw();
          var convertedToChartData = changeGraph(table);
          console.log(convertedToChartData);
          convertingData(convertedToChartData);
          myChartJS.data.labels = labels;
          myChartJS.data.datasets[0].data = hoursWorked;
          myChartJS.update(); 

      });
      $('#location-search').on('change', function() {
          table.column(1).search(this.value).draw();
          var convertedToChartData = changeGraph(table);
          console.log(convertedToChartData);
          convertingData(convertedToChartData);
          myChartJS.data.labels = labels;
          myChartJS.data.datasets[0].data = hoursWorked;
          myChartJS.update(); 


      });
      $('#merchandiser-search').on('change', function() {
          table.column(11).search(this.value).draw();
          var convertedToChartData = changeGraph(table);
          console.log(convertedToChartData);
          convertingData(convertedToChartData);
          myChartJS.data.labels = labels;
          myChartJS.data.datasets[0].data = hoursWorked;
          myChartJS.update(); 

      });
      $('#period-search').on('change', function() {
          // console.log(this.value);
          
          if (this.value.includes('to')) {
              const parts = this.value.split('to');
              // console.log('parts: ', parts);

              var start = parts[0].trim(); // Remove leading/trailing spaces
              startDate = start.replace(/^\s+/, ''); // Remove the first space
              startDate=new Date(startDate);
              var startDate=formatDateYMD(startDate);
              // console.log("start date", startDate);

              var end = parts[1].trim(); // Remove leading/trailing spaces
              endDate = end.replace(/^\s+/, ''); // Remove the first space
              endDate=new Date(endDate);
              var endDate=formatDateYMD(endDate);
              // console.log("end date", endDate);

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
              // console.log(dateList.join('|'), 'umerrrr');
              table.column(8).search(dateList.join('|'), true, false, true).draw(); // Join and apply search terms
              var convertedToChartData = changeGraph(table);
              console.log(convertedToChartData);
              convertingData(convertedToChartData, startDate, endDate);
              myChartJS.data.labels = labels;
              myChartJS.data.datasets[0].data = hoursWorked;
              myChartJS.update(); 
          } else {
              console.log("The substring 'to' does not exist in the original string.");
          }
        
        });
        document.getElementById('clearDate').addEventListener('click', function () {
            table.column(8).search('', true, false).draw(); // Clear previous search
        });
  });