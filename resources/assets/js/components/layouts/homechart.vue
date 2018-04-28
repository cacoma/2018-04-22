<script>
import {
  HorizontalBar
} from 'vue-chartjs';
export default {
  props: ['data'],
  extends: HorizontalBar,
  mounted() {
    
    let Labels = new Array();
    let Percs = new Array();
    let Colors = new Array();
    
    if (this.data) {
      this.data.forEach(element => {
        Labels.push(element.stockName);
        Percs.push(element.perc);
        Colors.push(element.color);
      });
      //console.log(this.data);

      this.renderChart({
        labels: Labels,
        datasets: [{
            backgroundColor: Colors,
            data: Percs,
          }
        ]
      }, {
        responsive: true,
        maintainAspectRatio: false,
        legend: {
          display: false
        }
      })
      //console.log(Labels, Percs, Colors)
    } else {
      console.log('No data');
    }
  },
}
</script>