<template>
<div>
  <b-card-group deck>
    <!-- primeiro  card -->
    <b-card header="Portfolio por investimento" header-tag="header">

      <pie-chart :donut="false" prefix="R$" thousands="." decimal="," :data="this.pie" :messages="{empty: 'Sem dados'}"></pie-chart>

      <enlarge ref="pie">
        <pie-chart :donut="false" prefix="$" thousands="." decimal="," :data="this.pie" :messages="{empty: 'Sem dados'}"></pie-chart>
      </enlarge>


      <div slot="footer">
        <b-row align-h="end">
          <b-col cols="3">
            <b-button align-self="end" @click="showModal('pie')" variant="primary">Ampliar</b-button>
          </b-col>
        </b-row>
      </div>
    </b-card>
    <!-- segundo  card -->
    <b-card header="Histórico de ações na carteira" header-tag="header" title="Diário (ult. 30 dias)">

      <b-input-group>
        <b-form-select v-model="selected">
          <option slot="first" v-bind:value="null">-- none --</option>
          <option v-for="stock in stocks" v-bind:value="stock.value">{{ stock.text }}</option>
        </b-form-select>
        <!-- <b-button size="md" variant="primary" @click="goChart">
          Buscar
        </b-button> -->
      </b-input-group>
      <br>
      <br>

      <homechartint v-if="this.selected !== null" :chart-data="datacollection" :width="400" :height="200"></homechartint>

      <enlarge ref="homechartint">
        <b-input-group>
          <b-form-select v-model="selected" @input="goChart">
            <option slot="first" v-bind:value="null">-- none --</option>
            <option v-for="stock in stocks" v-bind:value="stock.value">{{ stock.text }}</option>
          </b-form-select>
          <!-- <b-button size="md" variant="primary" @click="goChart"> -->
            <!-- Buscar
          </b-button> -->
        </b-input-group>
        <br>
        <br>

        <homechartint v-if="this.selected !== null" :chart-data="datacollection" :width="800" :height="400"></homechartint>

      </enlarge>

      <div slot="footer">
        <b-row align-h="end">
          <b-col cols="3">
            <b-button align-self="end" @click="showModal('homechartint')" variant="primary">Ampliar</b-button>
          </b-col>
        </b-row>
      </div>
    </b-card>
  </b-card-group>
  <br>
  <b-card-group deck>
    <!-- terceiro  card -->
    <b-card header="Portfolio por tipo de investimento" header-tag="header">

      <bar-chart suffix="%" thousands="." decimal="," :messages="{empty: 'Sem dados'}" :data="this.portperfp"></bar-chart>
      <enlarge ref="barchart">
        <bar-chart suffix="%" thousands="." decimal="," :messages="{empty: 'Sem dados'}" :data="this.portperfp"></bar-chart>
      </enlarge>

      <div slot="footer">
        <b-row align-h="end">
          <b-col cols="3">
            <b-button align-self="end" @click="showModal('barchart')" variant="primary">Ampliar</b-button>
          </b-col>
        </b-row>
      </div>
    </b-card>
    <!-- quarto  card -->
    <b-card header="Resultado por tipo de investimento" header-tag="header">

      <line-chart :download="true" legend="bottom" :messages="{empty: 'Sem dados'}" prefix="R$ " decimal="," :curve="false" xtitle="Tempo" ytitle="Reais" :data="this.portperf"></line-chart>
      <enlarge ref="linechart">
        <line-chart :download="true" legend="bottom" :messages="{empty: 'Sem dados'}" prefix="R$ " decimal="," :curve="false" xtitle="Tempo" ytitle="Reais" :data="this.portperf"></line-chart>
      </enlarge>
      <div slot="footer">
        <b-row align-h="end">
          <b-col cols="3">
            <b-button align-self="end" @click="showModal('linechart')" variant="primary">Ampliar</b-button>
          </b-col>
        </b-row>
      </div>
    </b-card>
  </b-card-group>
</div>
</template>
<script>
/*jshint esversion: 6 */
export default {
  props: ['portperf', 'portperfp', 'pie', 'invests'],
  data() {
    return {
      stocks: [],
      selected: null,
      chartData: [],
      datacollection: [],
      datasets: [],
      Days: [],
      homeChart: [],
      Labels: [],
      Prices: [],
      Colors: [],
      chart2: [],
      enlargeItem: '',
    };
  },
  created: function() {
    //cria lista de stocks com seus ids para passar para chart interativo
    //simbolos (tickets) unicos
    const uniqueSymbol = [...new Set(this.invests.map(item => item.stockName))];
    //ids dos simbolos (tickets) unicos
    const uniqueStock_id = [...new Set(this.invests.map(item => item.stock_id))];
    //monta objeto com os dados acima
    for (let i = 0; i < uniqueSymbol.length; i++) {
      this.stocks.push({
        text: uniqueSymbol[i],
        value: uniqueStock_id[i]
      });
    }
    //pega os meses para o grafico interativo
    //this.Months = [...new Set(this.monthlyquotes.map(element => moment(String(element.timestamp)).format('YYYY-MM')))];

    // let uniqueStockName = [...new Set(this.results.map(item => item.stockName))];
    //
    // let uniquePercentage2 = [...new Set(this.results.map(item => item.percentage))];
    //
    // let uniqueColor2 = [...new Set(this.results.map(item => item.color))];
    //
    // for (let i = 0; i < uniqueStockName.length; i++) {
    //   this.chart2.push({
    //     stockName: uniqueStockName[i],
    //     perc: uniquePercentage2[i],
    //     color: uniqueColor2[i],
    //   });
    // }
  },
  methods: {
    showModal(name) {
      this.$refs[name].enlarge();
    },
    goChart() {
      console.log(this.selected);
      this.Days = [];
      this.Labels = [];
      this.Prices = [];
      //this.chartData = [];
      //this.datacollection = [];
      axios.get('/api/daily/getintchart', {
          params: {
            query: this.selected
          }
        })
        // .then(response => response.data)
        .then(response => {
          //Vue.set(this, chartData, response.data);
          this.chartData = response.data;
        // })
        // .then(() => {
          if (this.chartData) {
            this.chartData.forEach(element => {
              this.Days.push(moment(String(element.timestamp)).format('MM-DD'));
              this.Prices.push(element.close);
            });
            this.Labels = [...new Set(this.chartData.map(item => item.stock_id))];
            this.datacollection = {
              labels: this.Days,
              datasets: [{
                label: this.Labels,
                backgroundColor: '#FC2525',
                data: this.Prices
              }]
            };
            //set(this , 'datacollection', temp )
          } else {
            console.log("erro");
          }
        })
        .catch(error => {
          console.log(error);
        });
      //.then(response => {
      //this.chartData = response.data;
    },
  }
};
</script>
