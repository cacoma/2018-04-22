<template>
<div>
  <b-carousel id="carousel1" style="text-shadow: 1px 1px 2px #333;" controls indicators background="#ababab" :interval="4000" img-width="1024" img-height="480" v-model="slide" @sliding-start="onSlideStart" @sliding-end="onSlideEnd">

    <!-- primeiro slide -->
    <b-carousel-slide img-blank img-alt="Blank image">
      <pie-chart :donut="false" prefix="$" thousands="." decimal="," :data="this.pie" :messages="{empty: 'Sem dados'}"></pie-chart>
      <br>
      <h1>
          Composição de portfolio
        </h1>
    </b-carousel-slide>

    <!-- segundo slide -->
    <b-carousel-slide img-blank img-alt="Blank image">
      <line-chart :download="true" legend="bottom" :messages="{empty: 'Sem dados'}" prefix="R$ " decimal="," :curve="false" xtitle="Tempo" ytitle="Reais" :data="this.portperf"></line-chart>
      <br>
      <h1>Performance do portfolio</h1>
    </b-carousel-slide>

    <!-- terceiro slide -->
    <b-carousel-slide img-blank img-alt="Blank image">
      <bar-chart suffix="%" thousands="." decimal="," :messages="{empty: 'Sem dados'}" :data="this.portperfp"></bar-chart>
      <br>
      <h1>
          Performance %
        </h1>
    </b-carousel-slide>

    <!--       quarto slide -->
    <!--       <b-carousel-slide img-blank img-alt="Blank image">

      </b-carousel-slide> -->

    <!--       quinto slide -->
    <!--       <b-carousel-slide img-blank img-alt="Blank image">
        <p>
          Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse
          eros felis, tincidunt a tincidunt eget, convallis vel est. Ut pellentesque
          ut lacus vel interdum.
        </p>
      </b-carousel-slide> -->

    <!--       fim do carousel -->
  </b-carousel>

  <!--     <p class="mt-4">
      Slide #: {{ slide }}<br>
      Sliding: {{ sliding }}
    </p> -->

</div>
</template>

<script>
export default {
  props: ['results', 'portperf', 'portperfp', 'pie'],
  data() {
    return {
      slide: 0,
      sliding: null,
    }
  },
  methods: {
    onSlideStart(slide) {
      this.sliding = true
    },
    onSlideEnd(slide) {
      this.sliding = false
    }
  }
}
</script>
