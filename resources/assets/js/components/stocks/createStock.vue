<template>
<div class="container-fluid">
  <b-card bg-variant="light">
    <b-alert variant="danger" dismissible :show="this.form.errors.any()" @dismissed="showDismissibleAlert=false">
      Erro!
    </b-alert>
    </b-alert>
    <b-form @submit="onSubmit" @reset="onReset" v-if="this.show" @input="form.errors.clear($event.target.name)">
      <b-col>
  <div v-for="(val, key, index) in form">
        <b-form-group v-bind:id="key + 'label'" v-bind:label="this.racaz.columnName(key)" v-bind:label-for="key" v-if="key != 'errors' && key != 'modal'">
          
            <b-form-input v-mask="'AAAA#.AA'" v-if="key == 'symbol'" :ref="key" v-on:input="updateData(key)" v-bind:id="key" v-bind:name="key" v-bind:class="{ 'is-invalid': form.errors.has(key) }" oninput="setCustomValidity('')" oninvalid="this.setCustomValidity('Insira a quantidade.')" required>
            </b-form-input>
          
          <b-form-input v-if="key == 'type'" :ref="key" v-on:input="updateData(key)" v-bind:id="key" v-bind:name="key" v-bind:class="{ 'is-invalid': form.errors.has(key) }" oninput="setCustomValidity('')" oninvalid="this.setCustomValidity('Insira a quantidade.')" required>
            </b-form-input>
            <p class="text-danger" v-if="form.errors.has(key)" v-text="form.errors.get(key)">
            </p>
          </b-form-group>
  </div>
        </b-col>
      <b-row align-h="end">
        <b-col md="2" offset-md="1">
          <b-button type="submit" variant="success" :disabled="form.errors.any()">Inserir</b-button>
          <b-button type="reset" variant="danger">Limpar</b-button>
        </b-col>
      </b-row>
  </b-form>
  </b-card>

  <!-- modal de sucesso -->
  <b-modal ref="myModalRef" id="myModalRef" hide-footer size="sm" centered v-model="form.modal.any()">
    <div class="d-block text-center">
      <h3 v-text="form.modal.get('message')"></h3>
      </b-button>
    </div>
  </b-modal>
  </div>
</template>
<script>
  import {
    Errors,
    Form,
    Modal
  } from './../../javascript/classes.js';

  export default {
    data() {
      return {
        form: new Form({
          symbol: 'teste',
          type: ''
        }),
        show: true,
      }
    },
    created: function() {
    },
    methods: {
      updateData(key) {
        //this.$emit('b-form-input', this.form.symbol)
        //alert(key)
        this.form[key] = this.$refs[key][0].localValue
      },
      onSubmit(evt) {
        evt.preventDefault();
        this.form.post('/stocks/store')
          .then(data => console.log('promise' + data))
          .catch(errors => console.log('promise' + errors)
                );
        this.show = false;
        this.$nextTick(() => {
          this.show = true
        });
      },
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
    }
  }
</script>