<template>
<b-modal dusk="flashmodal" ref="flashModal" id="flashModal" hide-footer size="md" centered v-model="this.show" :header-bg-variant="this.headerBgVariant" no-close-on-backdrop no-close-on-esc hide-header-close>
  <div class="d-block text-center">
    <h5 v-text="this.title"></h5>
        <div v-for="(value, key, index) in this.body" v-if="isJsonTest">
          <h3>{{ key }} : {{ value }} </h3>
        </div>
        
      <div v-for="(value, key, index) in this.body" v-if="!isJsonTest">
          <h3>{{ value }} </h3>
        </div>
<b-btn variant="outline-success" block @click="hide()">Ok</b-btn>
  </div>
</b-modal>
</template>

<script>
export default {
  props: ['message'],
  data() {
    return {
      show: false,
      body: {},
      title: '',
      headerBgVariant: 'secondary',
      dusk: 'ok',
      isJsonTest: false,
    }
  },
  created() {
    if (this.message) {
      this.flash(this.message)
    }
    window.events.$on('flash', (message) => this.flash(message))
  },
  methods: {
    flash(message) {
      this.body = {};
      this.title = '';
      this.headerBgVariant = 'secondary';
      if (this.isJson(message)){
      Object.keys(message).forEach( key => {
            if (key == 'message') {
            var myarr = message[key].toString().split("|");
            this.headerBgVariant = myarr[1];
            this.title = myarr[0];
            //console.log(myarr);
            } else {
            //this.body.push(key + " : " + message[key]);
            this.body[racaz.columnName(key)] = message[key];
            //console.log('Key : ' + key + ', Value : ' + message[key])
            }
        });
      console.log("isJson");
      this.isJsonTest = true;
      this.show = true;
      } else {
        console.log(message);
      var myarr2 = message.toString().split("|");
      this.show = true;
      this.headerBgVariant = myarr2[1];
      this.body.Servidor = myarr2[0];
      this.title = 'Aviso';
      this.isJsonTest = false;
      console.log('notJson');
      }

//       setTimeout(() => {
//         this.hide()
//       }, 5000)
    },
    hide() {
          this.show = false;
          // this.body = {};
          // this.title = '';
          // this.headerBgVariant = 'secondary';
    },
    isJson(item) {
    item = typeof item !== "string"
        ? JSON.stringify(item)
        : item;

    try {
        item = JSON.parse(item);
    } catch (e) {
        return false;
    }

    if (typeof item === "object" && item !== null) {
        return true;
    }

    return false;
}
  }
}
</script>
