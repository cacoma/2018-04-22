<template>
<moldura>
<b-list-group >
<div v-for="(value, key, index) in this.consolidated">
  <b-list-group-item href="#" class="flex-column align-items-start">
    <div class="d-flex w-80 justify-content-between">
      <h5 class="mb-1">{{ value.symbol }}</h5>
      <small class="text-muted">Valor médio: {{this.racaz.currFormatter.format(value.avgprice)}} </small>
    </div>
    <p class="mb-1">
      <b-list-group>
        <b-list-group-item href="#">Quantidade: {{parseInt(value.sumquant)}}</b-list-group-item>
        <b-list-group-item href="#">Preço médio: {{this.racaz.currFormatter.format(value.avgprice)}}</b-list-group-item>
        <b-list-group-item href="#">Total: {{this.racaz.currFormatter.format(value.total)}}</b-list-group-item>
      </b-list-group>
    </p>
    <!-- <small class="text-muted">Donec id elit non mi porta.</small> -->
  </b-list-group-item>

</div>
</b-list-group>
</moldura>
</template>
<script>
export default {
  data() {
    return {
      consolidated: {},
    }
  },
  created: function() {
    this.fetchConsolidatedInvests();
  },
  methods: {
    fetchConsolidatedInvests() {
      axios.get('/api/consolidated/')
      .then((response) => {
          for (let k in response.data) {
            if (typeof response.data[k] !== 'function') {
              Vue.set(this.consolidated, [k], response.data[k]);
            }
          }
          console.log('no erro!');
        })
        .catch(error => {
            console.log('erro!');
          })
        },
      }
    }
</script>
