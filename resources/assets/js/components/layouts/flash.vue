<template>
<b-modal ref="flashModal" id="flashModal" hide-footer size="md" centered v-model="this.show" :header-bg-variant="this.headerBgVariant">
  <div class="d-block text-center">
    <h5 v-text="this.title"></h5>
        <div v-for="(value, key, index) in this.body">
          <h3>{{ key }} : {{ value }} </h3>
        </div>
<!--         <h3 v-text="this.simpleBody"></h3>   -->

<!--     <div v-for="this.bod in this.body">
      <h3 v-text="this.bod"></h3>
  </div> -->
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
      this.show = true;
      } else {
        console.log(message);
      var myarr2 = message.toString().split("|");
      this.show = true;
      this.headerBgVariant = myarr2[1];
      this.body.Servidor = myarr2[0];
      this.title = 'Aviso';
      console.log('notJson');
      }

      setTimeout(() => {
        this.hide()
      }, 5000)
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
