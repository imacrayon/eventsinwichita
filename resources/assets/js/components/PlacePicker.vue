<template>
  <div class="placepicker">
    <input type="text" name="place_id" id="place_id" placeholder="Place or address" autocomplete="off" autocorrect="off" autocapitalize="off" required :value="value.name" @keydown="navigate($event)" @keyup="search($event)">
    <ul v-show="showResults" class="placepicker-menu">
      <li v-for="(result, index) in results" @click="select(result)" @mouseover="selected = index" class="placepicker-item" :class="index === selected ? 'selected' : ''" tabindex="-1">
        {{ result.name }}<br>
        <small>{{ result.street || 'N/A' }}</small>
      </li>
    </ul>
  </div>
</template>

<script>
export default {
  props: {
    source: {
      required: true
    },
    value: {
      type: Object,
      required: true,
      default: { name: '' }
    }
  },

  data () {
    return {
      showResults: false,
      results: [],
      selected: null
    }
  },

  methods: {
    navigate (e) {
      switch (e.which) {
        case 13: // Enter
          if (this.selected !== null) {
            e.preventDefault() // Prevent form from submitting
            this.select(this.results[this.selected])
          }
          break
        case 38: // Up
            e.preventDefault()
            this.selected--
            if (this.selected < 0) {
              this.selected = null
              this.showResults = false
            }
            break
        case 40: // Down
          e.preventDefault()
          if (this.selected !== null) {
            this.selected++
            if (this.selected > this.results.length - 1) {
              this.selected = 0
            }
          } else {
            this.showResults = true
            this.selected = 0
          }
          break
        case 27: // Esc
          e.preventDefault()
          this.showResults = false
          break
      }
    },

    search (e) {
      if (e.which !== 13 && e.which !== 38 && e.which !== 40 && e.which !== 27) {
        this.showResults = true
        this.selected = null
        this.results = []
        this.$emit('input', {name: e.target.value})
        const value = e.target.value.trim()
        if (value) {
          const query = value.toUpperCase()
          this.results = this.source.filter(place => {
            return (place.name && place.name.toUpperCase().indexOf(query) !== -1) ||
              (place.street && place.street.toUpperCase().indexOf(query) !== -1)
          }).slice(0, 4)
          // Automatically select if there is an exact match.
          // This is used to select programmatically set values
          if (this.results.length && value === this.results[0].name) {
            this.select(this.results[0])
          }
        }
      }
    },

    select (result) {
      this.$emit('input', result)
      this.selected = null
      this.showResults = false
    }
  }
}
</script>

<style lang="scss">
.placepicker {
  display: block;
  position: relative;
  &-menu {
    background: #fff;
    border-radius: 4px;
    list-style: none;
    margin: 0;
    padding: 0;
    position: absolute;
    left: 0;
    top: 100%;
    right: 0;
    z-index: 10;
    box-shadow: 0 2px 3px 0 rgba(34,36,38,.15);
  }
  &-item {
    display: block;
    font-size: .75rem;
    line-height: 1.2;
    padding: .5rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    border-bottom: 1px solid #e6e6e6;
    cursor: pointer;
    &:last-child {
      border: none;
    }
  }
  &-item small {
    font-size: .5rem;
  }
  &-item.selected {
    background-color: #e6e6e6;
  }
}
</style>
