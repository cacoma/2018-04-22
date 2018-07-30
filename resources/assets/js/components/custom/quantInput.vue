<template>
<input type="tel" @change="updateQuant($event.target.value)" ref="input">
</template>
<script>
  export default {
    props: {
      value: {
        required: true,
        type: [Number, String],
        default: 0
      },
      masked: {
        type: Boolean,
        default: false
      },
      precision: {
        type: Number,
        default: 2
      },
    },
    data() {
      return {
        formattedValue: '',
        quant: [0, 0],
        fValue: 0
      }
    },
    watch: {
      value: {
        //immediate: true,
        deep: true,
        handler: function(e, t) {
          console.log(e)
          console.log(typeof e)
          console.log(t)
      if (!e) {
        e = 0
      } 
       if (this.value === '') {
        console.log('chamou na alteracao')
        this.updateQuant('0');
        // e = 0
      }
       this.updateQuant(e.toString().replace(".", ","));
        // return Number(e)
          //console.log(t)
        }
      }
    },
    mounted: function() {
      if (this.value) {
        this.updateQuant(this.value.toString().replace(".", ","));
      } else {
        this.updateQuant('0');
      }
    },
    methods: {
      updateQuant(inputValue) {
        //this.value = e.target.value
        this.formattedValue = this.process(inputValue.toString().replace(/[^0-9,]/g, ''))
        if (!this.formattedValue || this.formattedValue === "") {
          this.formattedValue = "0"
        }
        if (this.formattedValue.includes(",")) {
          this.quant = this.formattedValue.split(",")
          //         this.quant[1] = (Number(this.quant[1].substring(0,this.precision)) + 100)/100
          //         this.quant[0] = Number(this.quant[0])        

          this.quant[1] = this.quant[1].substring(0, this.precision)
          //this.fValue = parseFloat(this.quant.join(".").toFixed(this.precision))
          // this.formattedValue = this.fValue.toString().replace(".",",")
          this.formattedValue = parseFloat(this.quant.join(".")).toFixed(this.precision).replace(".", ",")
          this.fValue = parseFloat(this.roundTo(parseFloat(this.quant.join(".")), this.precision))
        } else {
          // this.fValue = parseInt(this.formattedValue, this.precision)
          //this.fValue = parseFloat(this.formattedValue.toFixed(this.precision))
          this.fValue = parseFloat(this.roundTo(parseFloat(this.formattedValue, this.precision)))
          this.formattedValue = this.fValue.toFixed(this.precision).replace(".", ",")
        }

        // preenche a mascara
//         if (typeof this.formattedValue !== 'undefined') {
//         this.$refs.input.value = "0"
//         }
        this.$refs.input.value = this.formattedValue
        // preenche o value do input
        this.$emit("input", this.masked ? inputValue : this.fValue)
        // this.$emit("input", this.masked ? e.target.value : n.i(u.d)(e.target.value, this.precision))
      },
      //remove todas as virgulas, menos a primeira
      process(input) {
        var index = input.indexOf(',');
        if (index > -1) {
          input = input.substr(0, index + 1) +
            input.slice(index).replace(/\,/g, '');
        }
        return input;
      },
      //recebe um numero (n) e retorna uma string com o valor arredondado (digits)
      roundTo(n, digits) {
        var negative = false;
        if (digits === undefined) {
          digits = 0;
        }
        if (n < 0) {
          negative = true;
          n = n * -1;
        }
        var multiplicator = Math.pow(10, digits);
        n = parseFloat((n * multiplicator).toFixed(11));
        n = (Math.round(n) / multiplicator).toFixed(digits);
        if (negative) {
          n = (n * -1).toFixed(digits);
        }
        return n;
      },
    }
    //     render(h) {
    //       return h('input', {
    //         attrs: {
    //           type: "tel",
    //          // ref: 'quantInt'
    //         },
    //         domProps: {
    //           value: this.formattedValue
    //         },
    //         on: {
    //           change: e => {
    //                       this.updateQuant
    //                       // this.$emit("input", this.masked ? e.target.value : this.value)
    //                       //this.$emit('input', e.target.value)
    //           }
    //         }
    //       })
    //     },
  }
</script>