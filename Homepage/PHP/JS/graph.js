function makeDoubleChart(ChartName, Xdata, Y1data, Y2data){
    const xValues = Xdata;
    const y1Values = Y1data;
    const y2Values = Y2data;

    new Chart(ChartName, {
        type: "line",
        data: {
            labels: xValues,
            datasets: [{
                borderColor: "rgba(0, 0, 255, 0.87)",
                data: y1Values,
                fill: false
            },{
                borderColor: "rgba(255, 0, 0, 0.93)",
                data: y2Values,
                fill: false
            }]
        },
        options:{...}
    });
}
function makeTripleChart(ChartName, Xdata, Y1data, Y2data, Y3data){
    const xValues = Xdata;
    const y1Values = Y1data;
    const y2Values = Y2data;
    const y3Values = Y3data;

    new Chart(ChartName, {
        type: "line",
        data: {
            labels: xValues,
            datasets: [{
                borderColor: "rgba(0, 0, 255, 0.87)",
                data: y1Values,
                fill: false
            },{
                borderColor: "rgba(255, 0, 0, 0.93)",
                data: y2Values,
                fill: false
            },{
                borderColor: "rgba(0, 255, 13, 0.93)",
                data: y3Values,
                fill: false
            }]
        },
        options:{
            legend: {display: false}
        }
    });
}