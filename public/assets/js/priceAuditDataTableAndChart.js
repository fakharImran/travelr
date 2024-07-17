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
function setCardAndGrapgData(table) 
{
    var minProductPrice = Number.MAX_VALUE;
    var maxProductPrice = Number.MIN_VALUE;

    console.log(minProductPrice, maxProductPrice);

    var sumProductPrices = 0;
    var sumCompititorProductPrices = 0;
    var numberOfStore = 0; // Initialize the count of unique stores
    var sumCompititorProductPrices=0;  
    // Use a Set to keep track of unique stores
    var uniqueStores = new Set();
    var uniqueLocation = new Set();
    var storeLocation= new Set();

    // Iterate over the visible rows and calculate the minimum and maximum product prices
    table.rows({ search: 'applied' }).every(function (rowIdx, tableLoop, rowLoop) {
        const data = this.data();
        var store = data[1]; // Assuming column 1 contains the store
        var location = data[2]; // Assuming column 1 contains the store

        var tempStoreLoc= store +' '+ location;

        var productPrice = parseFloat(data[6]); // Assuming column 6 contains the product price
        var compititorProductPrice = parseFloat(data[10]); // Assuming column 6 contains the product price
        sumCompititorProductPrices+=compititorProductPrice;

        // console.log('dataaa', data);

        if (!isNaN(productPrice)) {
            sumProductPrices += productPrice;
            uniqueStores.add(store);
            uniqueLocation.add(location);
            storeLocation.add(tempStoreLoc);

            if (productPrice < minProductPrice) {
                minProductPrice = productPrice;
            }

            if (productPrice > maxProductPrice) {
                maxProductPrice = productPrice;
            }
        }
        minProductPrice = parseFloat(minProductPrice.toFixed(2));
        maxProductPrice = parseFloat(maxProductPrice.toFixed(2));
    });
    if(minProductPrice==Number.MAX_VALUE && maxProductPrice==Number.MIN_VALUE)
    {
        document.getElementById('minProductPrice').innerHTML = "$0";
        document.getElementById('maxProductPrice').innerHTML = "$0";
        document.getElementById('averageProductPrice').innerHTML = "$0";
        document.getElementById('compititorProductPrice').innerHTML = "$0";
    }
    else
    {
            // Calculate the average product price after the loop
        numberOfStore = uniqueStores.size; // Count of unique stores
        numberOfLocation = uniqueLocation.size; // Count of unique stores
        numberOfStoreLocation = storeLocation.size; // Count of unique stores
console.log('sumProductPrices', sumProductPrices);
console.log('sumCompititorProductPrices', sumCompititorProductPrices);
        console.log('numberOfStore',numberOfStore, ' numberOfLocation ', numberOfLocation, 'storeLocation', numberOfStoreLocation);

        var averageProductPrice = sumProductPrices / numberOfStoreLocation;
        var averageCompititorProductPrice = sumCompititorProductPrices / numberOfStoreLocation;
        // alert(averageCompititorProductPrice);
        // alert(parseFloat(averageCompititorProductPrice.toFixed(2)));

        document.getElementById('minProductPrice').innerHTML = '$'+minProductPrice;
        document.getElementById('maxProductPrice').innerHTML = '$'+maxProductPrice;
        document.getElementById('averageProductPrice').innerHTML = '$'+parseFloat(averageProductPrice.toFixed(2));
        document.getElementById('compititorProductPrice').innerHTML = '$'+parseFloat(averageCompititorProductPrice.toFixed(2));
    }
    var convertedToChartData = changeGraph(table, averageCompititorProductPrice);
    console.log("convertedToChartData", convertedToChartData);
    myChartJS.data.labels = convertedToChartData[0].products_name;
    
    myChartJS.data.datasets[0].data = convertedToChartData[0].products_price;
    // myChartJS.data.datasets[1].data = convertedToChartData[0].competitor_products_price;
    myChartJS.update();
}

const data = {
    labels: products_name,
    datasets: [{
      label: 'Price',
      data: products_price,
      backgroundColor: [
        '#1BC018',
        '#1892C0'
      ],
      borderColor: [
        '#000',
        '#000'
      ],
      borderWidth: 1
    }]
  };
const config = {
    type: 'bar',

    // type: 'bar',
    data: data,
    options: {
        legend: {
            display: false
         }, 
        scales: {
            xAxes: [{
                barPercentage: 0.4
            }],
            yAxes: [{
                scaleLabel: {
                    display: true,
                    labelString: 'Price $'
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
                    return data.datasets[tooltipItem.datasetIndex].label + ': $' + tooltipItem.yLabel
                }
            }
        },
      indexAxis: 'y',
      elements: {
        bar: {
          borderWidth: 2,
        }
      },
      responsive: true,
    },
  };

var myChartJS = new Chart(
    document.getElementById('myChart'),
    config
);

function update_price_comparison_card(row, averageCompititorProductPrice) {
    console.log(averageCompititorProductPrice);
   
    let total_product_price = row[6];
    let total_competetor_price= row[10];

    // dd($totalCompetetorPrice);
    
    let price_comparisan = ((total_product_price/total_competetor_price)*100)-100;
    price_comparisan= price_comparisan.toFixed(1);

    console.log('total_product_price', total_product_price, 'total_competetor_price', total_competetor_price, 'price_comparisan', price_comparisan );
    let element = document.getElementById('price_comparison');
    if(!isNaN(price_comparisan)){
        if(price_comparisan>averageCompititorProductPrice )
        {
            element.innerHTML = '<span style="color:  #1892C0">' + price_comparisan + '%</span>';                                
        }
        else if(price_comparisan<averageCompititorProductPrice) {
            element.innerHTML =  '<span style="color:  #1BC018">' + price_comparisan + '%</span>';                                
        }
        else
        {
            element.innerHTML =  '<span style="color:  #929293">' + price_comparisan + '%</span>';                                
        }
    }
    else{
        element.innerHTML =  '<span style="color:  #929293">' + '0' + '%</span>';  
    }
    
}

// datatable

//function for change the data it is comming from datatable search filters nnd getting it as required
function changeGraph(table, averageCompititorProductPrice) {
    var filteredIndexes = table.rows({ search: 'applied' }).indexes();
    var filteredData = [];
    let latest_row = [];
    let latest_date = new Date('2022');
    filteredIndexes.each(function (index) {
        let rowData = table.row(index).data();
        let current_date = new Date(rowData[0]);
        if(latest_date < current_date){
            latest_date = current_date;
            latest_row = rowData;
        }
    });
    let colData = [];
    let products_name = [];
    let products_price = [];

    products_name.push(latest_row[4]);
    products_name.push(latest_row[9]);
    products_price.push(latest_row[6]);
    products_price.push(latest_row[10]);

    update_price_comparison_card(latest_row, averageCompititorProductPrice);

    colData.push({'products_name':products_name, 'products_price':products_price});
    return colData;
}


$(document).ready(function () { 
    var table = $('#pricaAuditDatatable').DataTable({
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
    setCardAndGrapgData(table);

    // Custom search input for 'Name' column
    $('#store-search').on('change', function () {

        // Perform the search on the first column of the DataTable
        const searchValue = this.value.trim();
        table.column(1).search(searchValue ? `^${searchValue}$` : '', true, false).draw();
        // table.column(0).search(this.value).draw();

        setCardAndGrapgData(table);

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
            table.column(2).search('', true, false).draw(); // Clear previous search location
            dropdown.empty();
            dropdown.append('<option value="" selected>--Select--</option>');
        }
    });

    $('#location-search').on('change', function () {
        const searchValue = this.value.trim();

        table.column(2).search(searchValue ? `^${searchValue}$` : '', true, false).draw();
        setCardAndGrapgData(table);
    });
    $('#category-search').on('change', function () {
        const searchValue = this.value.trim();

        table.column(3).search(searchValue ? `^${searchValue}$` : '', true, false).draw();
        setCardAndGrapgData(table);
    });

    $('#merchandiser-search').on('change', function () {
        const searchValue = this.value.trim();
       
        table.column(13).search(searchValue ? `^${searchValue}$` : '', true, false).draw();
        setCardAndGrapgData(table);
    });

    // setCardAndGrapgData(table);
    $('#product-search').on('change', function () {
        const searchValue = this.value.trim();
        table.column(4).search(searchValue ? `^${searchValue}$` : '', true, false).draw();
        setCardAndGrapgData(table);
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

            setCardAndGrapgData(table);

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

            setCardAndGrapgData(table);
        
        }

    });

    document.getElementById('clearDate').addEventListener('click', function (element) {
        table.column(0).search('', true, false).draw(); // Clear previous search
        document.getElementById('period-search').clear;
        setCardAndGrapgData(table);
        document.getElementById('period-search').value = 'Date Range';

    });
});