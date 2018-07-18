<template>
<b-modal id="modal1" v-model="this.show" :title="this.header" hide-footer size="md" centered no-close-on-backdrop no-close-on-esc hide-header-close>
    <b-progress :value="this.progress" variant="info" striped class="mb-2" show-progress animated></b-progress>
<b-btn variant="outline-success" :disabled="this.progress < 100" block @click="hide()">Ok</b-btn>
  </b-modal>
</template>

<script>
export default {
  data () {
    return {
      show: false,
      progress: 0,
      header: '',
    }
  },
    watch: {
    // sempre que a pergunta mudar, essa função será executada
    progress: function (newProgress, oldProgress) {
      if (newProgress > 99) {
      this.hide()
      }
    },
    },
  created() {
    window.events.$on('progressBarValue', (value) => this.progress = value)
    window.events.$on('progressBar', (value) => this.progressBar(value))
  },
  methods: {
    progressBar(value) {
           this.header = value
           this.show = true;
        },
        hide() {
              this.show = false;
        },

}
}
</script>