<template>
<div class="container-fluid">
  <b-card bg-variant="light">
    <b-alert variant="danger" dismissible :show="this.form.errors.any()" @dismissed="showDismissibleAlert=false">
      Erro!
    </b-alert>
    </b-alert>
    <b-form @submit="onSubmit" @reset="onReset" v-if="this.show" @input="form.errors.clear($event.target.name)">
      <b-form-row>
        <b-col>
          <b-form-group id="symbollabel" label="Código" label-for="symbol">
            <input type="text" list="liststocks" placeholder="Código da ação" v-model="form.symbol" v-on:keyup="autoComplete" class="form-control" v-bind:class="{ 'is-invalid': form.errors.has('symbol') }" name="symbol" id="symbol" oninput="setCustomValidity('')" oninvalid="this.setCustomValidity('Insira o stock')" required>
            <datalist id="liststocks">
                   <option v-for="result in results" v-bind:value="result.symbol" v-bind:label="result.symbol"></option>
                </datalist>
            <p class="text-danger" v-if="form.errors.has('symbol')" v-text="form.errors.get('symbol')">
            </p>
            </b-tooltip>
          </b-form-group>
        </b-col>
        <b-col>
          <b-form-group id="datelabel" label="Data do investimento" label-for="dateinvest">
            <b-form-input id="dateinvest" name="dateinvest" type="date" v-model="form.dateinvest" v-bind:class="{ 'is-invalid': form.errors.has('dateinvest') }" oninput="setCustomValidity('')" oninvalid="this.setCustomValidity('Insira a data do investimento.')" required>
            </b-form-input>
            <p class="text-danger" v-if="form.errors.has('dateinvest')" v-text="form.errors.get('dateinvest')">
            </p>
          </b-form-group>
        </b-col>
        <b-col>
          <b-form-group id="brokerlabel" label="Corretora:" label-for="broker">
            <input type="text" list="listbrokers" placeholder="Corretora" v-model="form.broker" v-on:keyup="autoCompleteBroker" class="form-control" v-bind:class="{ 'is-invalid': form.errors.has('broker') }" name="broker" id="broker" oninput="setCustomValidity('')" oninvalid="this.setCustomValidity('Insira a corretora')" required>
            <datalist id="listbrokers">
                   <option v-for="resultbroker in resultbrokers" v-bind:value="resultbroker.name" v-bind:label="resultbroker.name"></option>
                </datalist>
            <p class="text-danger" v-if="form.errors.has('broker')" v-text="form.errors.get('broker')">
            </p>
          </b-form-group>
        </b-col>
      </b-form-row>
      <!--   segunda linha -->
      <b-form-row>
        <b-col>
          <b-form-group id="pricelabel" label="Preço" label-for="price">
            <b-input-group prepend="R$">
              <money id="price" name="price" v-model="form.price" v-bind="money" class="form-input input-lg form-control" v-bind:class="{ 'is-invalid': form.errors.has('price') }" oninput="setCustomValidity('')" oninvalid="this.setCustomValidity('Insira o preco')" required>
              </money>
            </b-input-group>
            <p class="text-danger" v-if="form.errors.has('price')" v-text="form.errors.get('price')">
            </p>
          </b-form-group>
        </b-col>
        <b-col>
          <b-form-group id="quantlabel" label="Quantidade:" label-for="quant">
            <b-form-input id="quant" name="quant" type="number" v-model="form.quant" v-bind:class="{ 'is-invalid': form.errors.has('quant') }" oninput="setCustomValidity('')" oninvalid="this.setCustomValidity('Insira a quantidade.')" required>
            </b-form-input>
            <p class="text-danger" v-if="form.errors.has('quant')" v-text="form.errors.get('quant')">
            </p>
          </b-form-group>
        </b-col>
        <b-col>
          <b-form-group id="brokerfeelabel" label="Corretagem:" label-for="brokerfee">
            <b-input-group prepend="R$">
              <money id="brokerfee" name="brokerfee" v-model="form.brokerfee" v-bind="money" class="form-input input-lg form-control" v-bind:class="{ 'is-invalid': form.errors.has('brokerfee') }" oninput="setCustomValidity('')" oninvalid="this.setCustomValidity('Insira o valor da corretagem.')" required>
              </money>
              <p class="text-danger" v-if="form.errors.has('brokerfee')" v-text="form.errors.get('brokerfee')">
              </p>
            </b-input-group>
          </b-form-group>
        </b-col>
        <b-col>
          <b-form-group id="totallabel" label="Total:" label-for="total">
            <b-input-group prepend="R$">
              <money id="total" :value="total" v-bind="money" class="form-input input-lg form-control" disabled>
              </money>
            </b-input-group>
          </b-form-group>
        </b-col>
      </b-form-row>
      <b-row align-h="end">
        <b-col md="2" offset-md="1">
          <b-button type="submit" variant="success" :disabled="form.errors.any()">Inserir</b-button>
          <b-button type="reset" variant="danger">Limpar</b-button>
        </b-col>
      </b-row>
    </b-form>
  </b-card>

  <!-- modal de investimento inserido -->
  <b-modal ref="myModalRef" id="myModalRef" hide-footer size="sm" centered v-model="form.modal.any()">
    <div class="d-block text-center">
      <h3 v-text="form.modal.get('message')"></h3>
    </div>
<!--     <b-btn class="mt-3" variant="outline-success" block data-dismiss="modal">Fechar</b-btn> -->
<!--     <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
  </b-modal>
</div>
</template>
<script>
  import {
    Errors,
    Form,
    Modal
  } from './../../javascript/classes.js';
  //import Form from './../../javascript/classErrors.js';

  export default {
    data() {
      return {
        form: new Form({
          symbol: '',
          dateinvest: '',
          broker: '',
          price: '',
          quant: '',
          brokerfee: '',
          //errors: new Errors(),
        }),
        money: {
          decimal: ',',
          thousands: '.',
          precision: 2,
          masked: false
        },
        csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        results: [],
        resultbrokers: [],
        show: true,
        locale: '',
      }
    },
    created: function() {
      this.locale = window.navigator.userLanguage || window.navigator.language;
      moment.locale(this.locale);
      this.form.dateinvest = moment().format('L');
    },
    computed: {
      // a computed getter para atualizar automaticamente o total
      total: function() {
        // `this` points to the vm instance
        return (this.form.price * this.form.quant) + this.form.brokerfee
      }
    },
    methods: {
      showModal() {
        this.$refs.myModalRef.show();
      },
      hideModal() {
        this.$refs.myModalRef.hide()
      },
      onSubmit(evt) {
        evt.preventDefault();
        this.form.post('/stocks/investstore')
          .then(data => console.log('promise' + data))
          .catch(errors => console.log('promise' + errors));
        //alert(JSON.stringify(this.form));
        // axios.post('/stocks/investstore', this.form)
        //   .then(this.onSuccess())
        //   .catch(error =>
        //     this.form.errors.record(error.response.data.errors)
        //   )
      },
      // onSuccess(response){
      //   this.showModal();
      //   this.form.reset();
      // },
      onReset(evt) {
        evt.preventDefault();
        this.form.reset();
        /* Reset our form values */
        /* Trick to reset/clear native browser form validation state */
        this.show = false;
        this.$nextTick(() => {
          this.show = true
        });
      },
      //busca as corretoras
      autoCompleteBroker() {
        this.resultsbrokers = [];
        if (this.form.broker.length > 2) {
          axios.get('/api/searchbrokers', {
              params: {
                query: this.querybroker
              }
            }).then(response => {
              this.resultbrokers = response.data;
            }).then(response => {
              console.log(response)
            })
            .catch(error => {
              console.log(error.response)
            });
        }
      },
      //busca as acoes
      autoComplete() {
        this.results = [];
        if (this.form.symbol.length > 2) {
          axios.get('/api/searchstocks', {
              params: {
                query: this.query
              }
            }).then(response => {
              this.results = response.data;
            }).then(response => {
              console.log(response)
            })
            .catch(error => {
              console.log(error.response)
            });
        }
      },
    },
  }
</script>