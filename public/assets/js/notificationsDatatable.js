
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
$(document).ready(function () { 
    var table = $('#notificationDatatable').DataTable({
        // Add your custom options here
        scrollX: true, // scroll horizontally
        paging: true, // Enable pagination
        searching: true, // Enable search bar
        ordering: true, // Enable column sorting
        lengthChange: false, // Show a dropdown for changing the number of records shown per page
        pageLength: 10, // Set the default number of records shown per page to 10
        // dom: 'lBfrtip', // Define the layout of DataTable elements (optional)
        buttons: ['copy', 'excel', 'pdf', 'print'], // Add some custom buttons (optional)
        "pagingType": "full_numbers"
    });
    // Custom search input for 'Name' column
    // $('#store-search').on('change', function () {

    //     // Perform the search on the first column of the DataTable
    //     const searchValue = this.value.trim();
    //     table.column(0).search(searchValue ? `^${searchValue}$` : '', true, false).draw();
    //     // table.column(0).search(this.value).draw();
    //     var storeName = this.value;

    //     // Assuming you have a dropdown with ID 'location-search'
    //     var dropdown = $('#location-search');

    //     allStores.forEach(function (store) {
    //         if (storeName == store[0]) {
    //             // Append each option into the select list
    //             // Append the column data to the dropdown
    //             table.column(1).search('', true, false).draw(); // Clear previous search
    //             dropdown.empty();
    //             dropdown.append('<option value="" selected>--Select--</option>');
    //             var storeLocations = store[1];
    //             storeLocations.forEach(function (storeLocation) {
    //                 dropdown.append('<option value="' + storeLocation + '">' + storeLocation + '</option>');
    //             });
    //         }
    //     });
    //     if (storeName == "") {
    //         // table.lengthMenu= [ [5, 10, 25, 50, -1], [5, 10, 25, 50, "All"] ];
    //         table.column(1).search('', true, false).draw(); // Clear previous search
    //         dropdown.empty();
    //         dropdown.append('<option value="" selected>--Select--</option>');
    //         allUniqueLocations.forEach(function (location) {
    //             // Append each option into the select list
    //             // Append the column data to the dropdown
    //             dropdown.innerHTML = '<option value="" selected>--Select--</option>';
    //             dropdown.append('<option value="' + location + '">' + location + '</option>');
    //         });
    //     }
    //     // Empty the dropdown to remove previous options
    // });

    // $('#location-search').on('change', function () {
    //     const searchValue = this.value.trim();
    //     table.column(1).search(searchValue ? `^${searchValue}$` : '', true, false).draw();
  
    // });
    $('#merchandiser-search').on('change', function () {
        const searchValue = this.value.trim();
        table.column(3).search(searchValue ? `^${searchValue}$` : '', true, false).draw();

    });
    // $('#period-search').on('change', function () {

    //     if (this.value.includes('to')) {
    //         const parts = this.value.split('to');

    //         var start = parts[0].trim(); // Remove leading/trailing spaces
    //         startDate = start.replace(/^\s+/, ''); // Remove the first space
    //         startDate = new Date(startDate);
    //          startDate = formatDateYMD(startDate);

    //         var end = parts[1].trim(); // Remove leading/trailing spaces
    //         endDate = end.replace(/^\s+/, ''); // Remove the first space
    //         endDate = new Date(endDate);
    //          endDate = formatDateYMD(endDate);

    //         table.column(0).search('', true, false).draw(); // Clear previous search

    //         var searchTerms = []; // Initialize an array to store search terms
    //         function dateRange(startDate, endDate) {
    //             var currentDate = new Date(startDate);
    //             var endDateObj = new Date(endDate);
    //             var dates = [];

    //             while (currentDate <= endDateObj) {
    //                 dates.push(formatDateYMD(new Date(currentDate)));
    //                 currentDate.setDate(currentDate.getDate() + 1);
    //             }
    //             return dates;
    //         }
    //         var dateList = dateRange(startDate, endDate);
    //         table.column(0).search(dateList.join('|'), true, false, true).draw(); // Join and apply search terms
         
    //     } else {
    //         console.log("The substring 'to' does not exist in the original string.");
    //     }

    // });

    // document.getElementById('clearDate').addEventListener('click', function (element) {
    //     table.column(0).search('', true, false).draw(); // Clear previous search
    //     document.getElementById('period-search').clear;
    //     endDate = 0;
    //     startDate = 0;
    //     document.getElementById('period-search').value = 'Date Range';

    // });
});