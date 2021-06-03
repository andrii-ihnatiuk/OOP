//* Performing API request
async function getStats() {
    try {
        let response = await fetch('https://bank.pnit.od.ua/bank/Stat');
        let stats = await response.json();
        return stats;
    } catch(error) {
        alert(error);
    }
}
//* Processing promise object
let data;
getStats().then(stats => {
    this.data = stats.data;
    initPage();
}).catch(error => {
    alert(error);
});

//* Data is ready -> can init page elements
function initPage() {
    console.log(this.data);
    
    $('#myTab').click(function(e) {
        if (typeof e.target.value !== 'undefined') {
            if (!$(e.target).hasClass('active')) {
                $('.nav-link.active').prev().removeClass('ic_active');
                $('.nav-link.active').removeClass('active');
                $(e.target).prev().addClass('ic_active');
                $(e.target).addClass('active');
            }
            if ($('.navbar_button').css('display') === 'flex') {
                $('.sidebar').css('display', 'none');
            }
            initChart(e.target.value);
        }
    });

    $('.navbar_button').click(function () {
        let sidebar = $('.sidebar');
        if (sidebar.css('display') === 'none') {
            sidebar.css('display', 'flex');
        }
        else {
            sidebar.css('display', 'none');
        }
    });

    let vh = window.innerHeight * 0.01;
    document.documentElement.style.setProperty('--vh', `${vh}px`);

    window.addEventListener('resize', () => {
        let vh = window.innerHeight * 0.01;
        document.documentElement.style.setProperty('--vh', `${vh}px`);
    });
        
    initChart('line');
}


var myChart; // global chart variable
var curChartType; // type of the active chart
//* Creating chart 
function initChart(type) {  

    if (type !== 'line' && type !== 'bar' && type !== 'pie' || type === this.curChartType) return; 
    if (this.myChart !== null && typeof this.myChart !== 'undefined') 
        destroyChart(); // if chart already exists we should destroy it

    let = config = {
        type: type,
        data: {
            datasets: [{
                label: 'Users per bank',
                backgroundColor: 'rgb(54, 106, 231 )',
                borderColor: 'rgb(54, 106, 231 )',
                data: this.data,
                hoverOffset: 4
            }]
        },
        options: {  }
    };
    // TODO : change config according to the selected type
    switch (type) {
        case 'line':    
        case 'bar':
            config.options = {
                parsing: {
                    xAxisKey: 'name',
                    yAxisKey: 'users'
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                elements: {
                    point: {
                    radius: 5,
                    hoverRadius: 7
                    }
                }
            };
            break;
       
        case 'pie':
            let labels = [];
            let users = [];

            this.data.forEach(e => {
                labels.push(e.name);
                users.push(e.users);
            });

            config.data.datasets[0].borderColor = 'white';
            config.data.datasets[0].data = users;
            config.data.labels = labels;
            config.options.maintainAspectRatio = false;
            config.options.aspectRatio = 0.5;
            config.options.layout = {padding: 5};
            
           var rgbColors = [];
            for (var i = 0; i <= this.data.length; i++) {
                rgbColors.push('rgb(' + colorGen() + ',' + colorGen() + ',' + colorGen() + ')');
            }

            config.data.datasets[0].backgroundColor = rgbColors;

            break;
        default:
            break;
    }
    // Creating the chart itself
    this.myChart = new Chart(
        document.getElementById('myChart'),
        config
    );
    this.curChartType = type;
}

function destroyChart() {
    this.myChart.destroy();
}

//* Generate random color *//
function colorGen () { 
    var generateColor = Math.floor(Math.random() * 256 );
    return generateColor;
}