<template>
  <input type="text" :placeholder="placeholder" :value="value" required>
</template>

<script>
import Flatpickr from 'flatpickr';

export default {
  props: {
    placeholder: {
      type: String,
      default: ''
    },
    options: {
      type: Object,
      default: () => ({})
    },
    value: {
      default: ''
    }
  },
  data () {
    return {
      fp: null,
    };
  },
  computed: {
    fpOptions () {
      return JSON.stringify(this.options)
    },
  },
  watch: {
    value (val) {
      if (this.fp) this.fp.setDate(val)
    },
    fpOptions (newOpt) {
      const option = JSON.parse(newOpt)
      for (let o in option) {
        if (option.hasOwnProperty(o)) {
          this.fp.set(o, option[o])
        }
      }
    },
  },
  mounted () {
    const self = this;
    const origOnValUpdate = this.options.onValueUpdate;
    this.$nextTick(() => {
      this.fp = new Flatpickr(this.$el, Object.assign(this.options, {
        onValueUpdate () {
          self.onInput(self.$el.value)
          if (typeof origOnValUpdate === 'function') {
            origOnValUpdate()
          }
        },
        enableTime: true,
        dateFormat: 'Y-m-d H:i:S',
        altInput: true,
        altFormat: 'F j, Y h:i K'
      }));
    })
  },
  destroyed () {
    this.fp.destroy()
    this.fp = null
  },
  methods: {
    onInput (e) {
      typeof e === 'string' ? this.$emit('input', e) : this.$emit('input', e.target.value)
    },
  },
};
</script>
