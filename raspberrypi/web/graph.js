<html>
<script src="https://cdn.zingchart.com/zingchart.min.js"></script>
<body>
<div id="myChart"></div>
</body>
<script>
  zingchart.render({
    id: 'myChart',
    data: {
      type: 'line',
      series: [{
        values: [54,23,34,23,43],
      }, {
        values: [10,15,16,20,40]
      }]
    }
  });
</script>
</html>