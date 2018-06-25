<template>
<moldura>
<b-card no-body>
  <b-tabs card>
  <b-tab title="Resumo consolidado" active>
    <br>
    <b-table striped hover :busy.sync="isBusy" :items="items" :fields="fields"></b-table>
  <b-list-group>
  <b-list-group-item class="text-right">Total: {{total}}</b-list-group-item>
<!--   <b-list-group-item active v-text="this.racaz.currFormatter.format(this.total)"></b-list-group-item> -->
  </b-list-group>
  </b-tab>
  <b-tab title="Outros resumos" >
    <br>I'm the second tab content
  </b-tab>
  <b-tab title="disabled" disabled>
    <br>Disabled tab!
  </b-tab>
</b-tabs>

</b-card>
  </moldura>
</template>
<script>
let items = {};
export default {
  data() {
    return {
      consolidated: {},
      isBusy: false,
      items: [],
      fields: [],
      total: 0,
    }
  },
  created: function() {
    this.provideData();

        console.log('entrou');
    //this.fetchConsolidatedInvests();
  },
  mounted: function() {
  },
  methods: {
    provideData() {
      this.isBusy = true;
      let promise = axios.get('/api/consolidated/')

      return promise.then((response) => {
          this.fields = racaz.fieldsFiller(Object.keys(response.data[0]));
          this.items = response.data;
          // Here we could override the busy state, setting isBusy to false
          this.isBusy = false;
          this.totalizator();
          return (this.items);
        })
        .catch(error => {
          // Here we could override the busy state, setting isBusy to false
          // this.isBusy = false
          // Returning an empty array, allows table to correctly handle busy state in case of error
        console.log(error);
          return []
        })
    },
    totalizator() {
          this.total = racaz.currFormatter.format(Object.keys(this.items).reduce((sum, key)  => {
                  return sum + parseFloat(this.items[key].total);
                  }, 0));
    }
      }
    }
</script>

