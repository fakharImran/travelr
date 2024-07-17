

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
function setCards(table)
{
    console.log(allUniqueLocations.length,'-------------------');
    console.log(allUniqueCategories,'-------------------');
    console.log(allUniqueProducts,'-------------------');
  
    const expiredStores = new Set(); // Use a Set to store unique store names
    const expiredCategories = new Set(); // Use a Set to store unique store names
    const expiredProducts = new Set(); // Use a Set to store unique store names
    const storeLocation = new Set(); // Use a Set to store unique store names
    const currentDate = new Date(); // Get the current date

    table.rows({ search: 'applied' }).every(function (rowIdx, tableLoop, rowLoop) {
        const data = this.data();
        const store = data[1]; // Assuming column 1 contains the store
        const location = data[2]; // Assuming column 1 contains the store
        const category= data[3];
        const product= data[4];
            
        var tempStoreLocation = store+ ' '+ location;
        storeLocation.add(tempStoreLocation);
        // Create a temporary element to parse the HTML content
        const tempElement = document.createElement('div');
        tempElement.innerHTML = data[8]; // Assuming data[8] contains the HTML content

        expiredStores.add(store); 
        expiredCategories.add(category); 
        expiredProducts.add(product); 
        
   
    });
    // console.log('storeLocation:', storeLocation);
    
    const numberOfStoreLocation = storeLocation.size;
    const numberOfexpStores = expiredStores.size;
    const numberOfexpCategories = expiredCategories.size;
    const numberOfexpProduct = expiredProducts.size;

    console.log('Number of unique expired stores Location:', numberOfStoreLocation);
    // console.log('Number of unique expired stores:', numberOfexpStores);
    console.log('Number of unique expired categories:', numberOfexpCategories);
    console.log('Number of unique expired stores:', numberOfexpProduct);

    if(numberOfStoreLocation==0)
    {
        document.getElementById('no_of_exp_store').innerHTML=numberOfStoreLocation+' / '+ allUniqueLocations.length;
    }
    else
    {
        document.getElementById('no_of_exp_store').innerHTML='<span style="color: #CA371B">'+numberOfStoreLocation+' /</span> '+ allUniqueLocations.length;
    }
    if(numberOfexpCategories==0)
    {
        document.getElementById('category_of_exp_product').innerHTML=numberOfexpCategories+' / '+ allUniqueCategories;
    }
    else
    {
        document.getElementById('category_of_exp_product').innerHTML='<span style="color: #CA371B">'+numberOfexpCategories+' /</span> '+ allUniqueCategories;
    }
    if(numberOfexpProduct==0)
    {
        document.getElementById('no_of_exp_product').innerHTML=numberOfexpProduct+' / '+ allUniqueProducts;
    }
    else
    {
        document.getElementById('no_of_exp_product').innerHTML='<span style="color: #CA371B">'+numberOfexpProduct+' /</span> '+ allUniqueProducts;
    }
    // document.getElementById('no_of_exp_store').innerHTML='<span style="color: #CA371B">'+numberOfStoreLocation+' /</span> '+ allUniqueLocations.length;
    // document.getElementById('category_of_exp_product').innerHTML='<span style="color: #CA371B">'+numberOfexpCategories+' /</span> '+ allUniqueCategories;
    // document.getElementById('no_of_exp_product').innerHTML='<span style="color: #CA371B">'+numberOfexpProduct+' /</span> '+ allUniqueProducts;
}
$(document).ready(function () { 
    var table = $('#productExpiryTrackerDatatable').DataTable({
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

    // Custom search input for 'Name' column
    $('#store-search').on('change', function () {

        // Perform the search on the first column of the DataTable
        const searchValue = this.value.trim();
        table.column(1).search(searchValue ? `^${searchValue}$` : '', true, false).draw();
        // table.column(0).search(this.value).draw();
       setCards(table);

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
    });

    $('#location-search').on('change', function () {
        const searchValue = this.value.trim();
        table.column(2).search(searchValue ? `^${searchValue}$` : '', true, false).draw();
        setCards(table);
  
    });
    $('#category-search').on('change', function () {
        const searchValue = this.value.trim();
        table.column(3).search(searchValue ? `^${searchValue}$` : '', true, false).draw();
        
       setCards(table);

    });
    
    $('#product-search').on('change', function () {
        const searchValue = this.value.trim();
        table.column(4).search(searchValue ? `^${searchValue}$` : '', true, false).draw();
       setCards(table);

    }); 
    $('#exp-dmg-search').on('change', function () {
        const searchValue = this.value.trim();
        table.column(6).search(searchValue ? `^${searchValue}$` : '', true, false).draw();
       setCards(table);

    });

    $('#merchandiser-search').on('change', function () {
        const searchValue = this.value.trim();
        table.column(11).search(searchValue ? `^${searchValue}$` : '', true, false).draw();
       setCards(table);


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
            setCards(table);
         
        } else {
             startDate = new Date(this.value);

             endDate = startDate;

            table.column(0).search('', true, false).draw(); // Clear previous search

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
            setCards(table);

        }

    });

    document.getElementById('clearDate').addEventListener('click', function (element) {
        table.column(0).search('', true, false).draw(); // Clear previous search
        document.getElementById('period-search').clear;
        endDate = 0;
        startDate = 0;
        document.getElementById('period-search').value = 'Date Range';

    });
});