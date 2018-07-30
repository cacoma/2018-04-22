<template>
<div class="container-fluid justify-content-center" v-if="this.show">
  <b-alert variant="danger" dismissible :show="this.form.errors.any()" @dismissed="showDismissibleAlert=false">
    <div v-for="(value, key, index) in this.form.errors.errors">
      {{ index + 1 }}. {{ this.racaz.columnName(key) }}: {{ value[0] }}
    </div>
  </b-alert>
  <b-form @submit="onSubmit" @reset="onReset" @input="form.errors.clear($event.target.name)" id="createtypefundform" dusk="createtypefundform" autocomplete="off">
    <b-card bg-variant="light">
      <input autocomplete="false" name="hidden" type="text" style="display:none;">
      <!-- <b-form-row v-if="!this.editMode">
      <b-col>
        <b-form-group label="Operação de: ">
          <b-form-radio-group id="signal" name="signal" buttons button-variant="outline-primary" size="md" v-model="form.signal" :options="optionsSignal" v-bind:class="{ 'is-invalid': form.errors.has('signal') }" required/>
        </b-form-group>
        <p class="text-danger" v-if="form.errors.has('signal')" v-text="form.errors.get('signal')">
        </p>
      </b-col>
    </b-form-row> -->

      <b-form-row>
        <b-col>
          <b-form-group id="cnpjlabel" label="Cnpj do fundo:" label-for="cnpj">
            <!-- <b-tooltip ref="tooltipCnpj" target="cnpj" v-show="tipName" placement="topright">
            <strong v-text="tipCnpj"></strong>
          </b-tooltip> -->
            <input disabled type="text" list="listcnpjs" placeholder="Nome do fundo" v-model="form.cnpj" autocomplete="off" class="form-control" v-bind:class="{ 'is-invalid': form.errors.has('cnpj') }" name="cnpj" id="cnpj" ref="cnpj" oninput="setCustomValidity(''); checkValidity(); setCustomValidity(validity.valid ? '' :'Insira o Nome do fundo.')"
              required>
            <!-- <datalist id="listcnpjs">
                   <option v-for="(value, key) in resultfunds" v-bind:value="key">{{key}} - {{ value }}</option>
            </datalist> -->
            <p class="text-danger" v-if="form.errors.has('cnpj')" v-text="form.errors.get('cnpj')">
            </p>
          </b-form-group>
        </b-col>

        <b-col cols='9'>
          <b-form-group id="namelabel" label="Nome do fundo:" label-for="name">
            <!-- <b-tooltip ref="tooltipName" target="name" v-show="tipName" placement="topright">
            <strong v-text="tipName"></strong>
          </b-tooltip> -->
            <input disabled type="text" list="listnames" placeholder="Nome do fundo" v-model="form.name" autocomplete="off" class="form-control" v-bind:class="{ 'is-invalid': form.errors.has('name') }" name="name" id="name" ref="name" oninput="setCustomValidity(''); checkValidity(); setCustomValidity(validity.valid ? '' :'Insira o Nome do fundo.')"
              required>
            <!-- <datalist id="listnames">
                   <option v-for="(value, key) in resultfunds" v-bind:value="value" >{{key}} - {{ value }}</option>
            </datalist> -->
            <p class="text-danger" v-if="form.errors.has('name')" v-text="form.errors.get('name')">
            </p>
          </b-form-group>
        </b-col>
      </b-form-row>


      <b-form-row>
        <b-col>
          <b-form-group id="datelabel" label="Data do investimento" label-for="date_invest">
            <datepicker disabled id="date_invest" :language="ptBR" name="date_invest" v-model="form.date_invest" input-class="form-control" v-bind:class="{ 'is-invalid': form.errors.has('date_invest') }" placeholder="Clique aqui para inserir a data." format="dd/MM/yyyy"
              :disabledDates="this.disabledDates" required dusk="datepicker">
            </datepicker>
            <p class="text-danger" v-if="form.errors.has('date_invest')" v-text="form.errors.get('date_invest')">
            </p>
          </b-form-group>
        </b-col>
        <b-col>
          <b-form-group id="brokerlabel" label="Corretora:" label-for="broker_name">
            <!-- <b-tooltip ref="tooltipBroker" target="broker_name" v-show="tipBroker" placement="topright">
            <strong v-text="tipBroker"></strong>
          </b-tooltip> -->
            <input disabled type="text" list="listbrokers" placeholder="Corretora" v-model="form.broker_name" autocomplete="off" class="form-control" v-bind:class="{ 'is-invalid': form.errors.has('broker_name') }" name="broker_name" id="broker_name" ref="broker_name"
              oninput="setCustomValidity(''); checkValidity(); setCustomValidity(validity.valid ? '' :'Insira a corretora.')" required>
            <!-- <datalist id="listbrokers">
                   <option v-for="resultbroker in resultbrokers" v-bind:value="resultbroker" class="text-light bg-dark">{{resultbroker}}</option>
            </datalist> -->
            <p class="text-danger" v-if="form.errors.has('broker_name')" v-text="form.errors.get('broker_name')">
            </p>
          </b-form-group>
        </b-col>
      </b-form-row>

      <!--   segunda linha -->
      <b-form-row>
        <b-col>
          <b-form-group id="pricelabel" label="Preço da cota" label-for="price">
            <b-input-group prepend="R$">
              <quantinput disabled id="price" name="price" v-model="form.price" v-bind="quantInputReais" class="form-input input-lg form-control" v-bind:class="{ 'is-invalid': form.errors.has('price') }" oninput="setCustomValidity(''); checkValidity(); setCustomValidity(validity.valid ? '' :'Insira o preço da cota.')"
                required>
              </quantinput>
            </b-input-group>
            <p class="text-danger" v-if="form.errors.has('price')" v-text="form.errors.get('price')">
            </p>
          </b-form-group>
        </b-col>
        <b-col>
          <b-form-group id="quantlabel" label="Quantidade:" label-for="quant">
            <quantinput disabled v-model="form.quant" v-bind="quantInput" class="form-input input-lg form-control" v-bind:class="{ 'is-invalid': form.errors.has('quant') }" oninput="setCustomValidity(''); checkValidity(); setCustomValidity(validity.valid ? '' :'Insira o preço da cota.')">
            </quantinput>
            <p class="text-danger" v-if="form.errors.has('quant')" v-text="form.errors.get('quant')">
            </p>
          </b-form-group>
        </b-col>
        <b-col>
          <b-form-group id="broker_feelabel" label="Corretagem:" label-for="broker_fee">
            <b-input-group prepend="R$">
              <quantinput disabled id="broker_fee" name="broker_fee" v-model="form.broker_fee" v-bind="quantInputReais" class="form-input input-lg form-control" v-bind:class="{ 'is-invalid': form.errors.has('broker_fee') }" oninput="setCustomValidity(''); checkValidity(); setCustomValidity(validity.valid ? '' :'Insira o valor da corretagem.')"
                required>
              </quantinput>
              <p class="text-danger" v-if="form.errors.has('broker_fee')" v-text="form.errors.get('broker_fee')">
              </p>
            </b-input-group>
          </b-form-group>
        </b-col>
        <b-col>
          <b-form-group id="totallabel" label="Total:" label-for="total">
            <b-input-group prepend="R$">
              <quantinput id="total" v-model="total" v-bind="quantInputReais" class="form-input input-lg form-control" disabled dusk="createtypefundtotal">
              </quantinput>
            </b-input-group>
          </b-form-group>
        </b-col>
      </b-form-row>
    </b-card>

    <!-- terceira linha -->
    <br>

    <b-card bg-variant="light">
      <b-form-row>
        <b-col>
          <b-form-group id="sellPricelabel" label="Preço da cota" label-for="sellPrice">
            <b-input-group prepend="R$">
              <quantinput id="sellPrice" name="sellPrice" v-model="form.sellPrice" v-bind="quantInputReais" class="form-input input-lg form-control" v-bind:class="{ 'is-invalid': form.errors.has('sellPrice') }" oninput="setCustomValidity(''); checkValidity(); setCustomValidity(validity.valid ? '' :'Insira o preço da cota.')"
                required>
              </quantinput>
            </b-input-group>
            <p class="text-danger" v-if="form.errors.has('sellPrice')" v-text="form.errors.get('sellPrice')">
            </p>
          </b-form-group>
        </b-col>
        <b-col>
          <b-form-group id="sellQuantlabel" label="Quantidade:" label-for="sellQuant">

            <quantinput v-model="form.sellQuant" v-bind="quantInput" class="form-input input-lg form-control" v-bind:class="{ 'is-invalid': form.errors.has('sellQuant') }" oninput="setCustomValidity(''); checkValidity(); setCustomValidity(validity.valid ? '' :'Insira o preço da cota.')">
            </quantinput>

            <p class="text-danger" v-if="form.errors.has('sellQuant')" v-text="form.errors.get('sellQuant')">
            </p>
          </b-form-group>
        </b-col>
        <b-col>
          <b-form-group id="sellBroker_feelabel" label="Corretagem:" label-for="sellBroker_fee">
            <b-input-group prepend="R$">
              <quantinput id="sellBroker_fee" name="sellBroker_fee" v-model="form.sellBroker_fee" v-bind="quantInputReais" class="form-input input-lg form-control" v-bind:class="{ 'is-invalid': form.errors.has('sellBroker_fee') }" oninput="setCustomValidity(''); checkValidity(); setCustomValidity(validity.valid ? '' :'Insira o valor da corretagem.')"
                required>
              </quantinput>
              <p class="text-danger" v-if="form.errors.has('sellBroker_fee')" v-text="form.errors.get('sellBroker_fee')">
              </p>
            </b-input-group>
          </b-form-group>
        </b-col>
      </b-form-row>
      <b-form-row>
        <b-col>
          <b-form-group id="sellDatelabel" label="Data do investimento" label-for="sellDate_invest">
            <datepicker id="sellDate_invest" :language="ptBR" name="sellDate_invest" v-model="form.sellDate_invest" input-class="form-control" v-bind:class="{ 'is-invalid': form.errors.has('sellDate_invest') }" placeholder="Clique aqui para inserir a data." format="dd/MM/yyyy"
              :disabledDates="this.disabledDates" required dusk="sellDatepicker">
            </datepicker>
            <p class="text-danger" v-if="form.errors.has('sellDate_invest')" v-text="form.errors.get('sellDate_invest')">
            </p>
          </b-form-group>
        </b-col>
        <b-col>
          <b-form-group id="sellTotallabel" label="Total:" label-for="sellTotal">
            <b-input-group prepend="R$">
              <quantinput id="sellTotal" v-model="sellTotal" v-bind="quantInputReais" class="form-input input-lg form-control" disabled dusk="createtypefundsellTotal">
              </quantinput>
            </b-input-group>
          </b-form-group>
        </b-col>
      </b-form-row>
    </b-card>

    <br>

    <b-row align-h="end">
      <b-col md="6" offset-md="1">
        <b-button type="submit" variant="success" :disabled="form.errors.any()" dusk="createtypefundsubmit"> {{ 'Vender' }}</b-button>
        <b-button type="reset" variant="danger">{{ 'Fechar' }}</b-button>
        <b-button variant="primary" @click="formReset" dusk="createformreset">{{ 'Limpar' }}</b-button>
      </b-col>
    </b-row>
  </b-form>
</div>
</template>
<script>
import {
  Errors,
  Form,
  Modal
} from './../../javascript/classes.js';

import Datepicker from 'vuejs-datepicker';
import {
  ptBR
} from 'vuejs-datepicker/dist/locale';

export default {
  components: {
    Datepicker
  },

  data() {
    return {
      form: new Form({
        signal: 'buy',
        cnpj: '',
        name: '',
        date_invest: '',
        broker_name: '',
        price: '',
        quant: '',
        broker_fee: '',
        sellDate_invest: '',
        sellPrice: '',
        sellQuant: '',
        sellBroker_fee: '',
      }),
      quantInput: {
        precision: 4,
        masked: false,
      },
      quantInputReais: {
        precision: 2,
        masked: false,
      },
      csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
      show: false,
      disabledDates: racaz.dateInvestLimit.disabledDates,
      ptBR: ptBR,
    }
  },
  watch: {

  },
  created: function() {
    window.events.$on('sellTypeFunds', (item) => this.populateData(item));
    this.$bus.$on('formHide', () => this.show = false);
  },
  computed: {
    // a computed getter para atualizar automaticamente o total
    total: {
      get: function() {
        // `this` points to the vm instance
        return (parseFloat(this.form.price) * parseFloat(this.form.quant)) + parseFloat(this.form.broker_fee);
      },
      set: function(newValue) {}
    },
    sellTotal: {
      get: function() {
        // `this` points to the vm instance
        return (parseFloat(this.form.sellPrice) * parseFloat(this.form.sellQuant)) - parseFloat(this.form.sellBroker_fee);
      },
      set: function(newValue) {}
    },
  },
  methods: {
    // setCnpj(e) {
    //   console.log('acessou setCnpj')
    //   var newName = e.target.value;
    //   console.log(newName)
    //   for (let prop in this.resultfunds) {
    //     if (this.resultfunds.hasOwnProperty(prop)) {
    //       if (this.resultfunds[prop] === newName) {
    //         this.form.cnpj = prop;
    //       }
    //     }
    //   }
    // },
    // setName(e) {
    //   console.log('acessou setName')
    //   var newCnpj = e.target.value;
    //   console.log(newCnpj)
    //   for (let prop in this.resultfunds) {
    //     if (prop === newCnpj) {
    //       this.form.name = this.resultfunds[prop]
    //     }
    //   }
    // },
    onSubmit(evt) {
      evt.preventDefault();
      // if (!this.editMode) {
      this.form.post('/operations/funds')
        .then(data => {
          console.log('promise success ' + data);
          // if (this.Slug2 !== 'create') {
          this.show = false;
          this.$bus.$emit('updateindexinvestedit', this.form);
          this.$bus.$emit('enlargeclose');
          this.formReset();
          // } else {}
        })
        .catch(errors => console.log('promise error' + errors));
      // } else {
      //   this.form.patch('/funds/invests/' + this.form.id)
      //     .then(data => {
      //       if (this.Slug2 !== 'create') {
      //         this.show = false;
      //         this.$bus.$emit('updateindexinvestedit', this.form);
      //         this.$bus.$emit('enlargeclose');
      //       } else {}
      //     })
      //     .catch(errors => {
      //       console.log('promise update fail');
      //     });
      // }
    },
    onReset(evt) {
      evt.preventDefault();
      // if (!this.editMode) {
      this.formReset();
      /* Reset our form values */
      /* Trick to reset/clear native browser form validation state */
      // this.show = false;
      this.$bus.$emit('enlargeclose');
      // } else {
      // this.show = false;
      // this.$bus.$emit('enlargeclose');
      // }
    },
    formReset() {
      this.form.resetSell();
    },
    //popula data dentro do proprio formulario
    populateData(value) {
      if (value) {
        console.log('populate acionado');
        // this.editMode = true;
        for (let k in value) {
          if (typeof value[k] !== 'function') {
            Vue.set(this.form, [k], value[k]);
          }
        }
        this.show = true;
      } else {
        this.formreset();
        // this.form.signal = 'buy';
        // this.tipBroker = 'Procurar corretora';
        // this.tipName = 'Procurar fundo pelo nome';
        // this.tipName = 'Procurar fundo pelo CNPJ';
        // this.editMode = false;
        this.show = true;
      }
    },
  },
  directives: {

  },
  filters: {}
}
</script>
