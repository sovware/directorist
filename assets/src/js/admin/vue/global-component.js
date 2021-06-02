import Vue from 'vue';

import upperFirst from 'lodash/upperFirst';
import camelCase from 'lodash/camelCase';

Vue.directive('click-outside', {
  priority: 700,

  bind () {
    let self  = this;
    this.event = function (event) { 
      console.log('emitting event')
      
    	self.vm.$emit( self.expression, event) 
    }
     
    this.el.addEventListener('click', this.stopProp);
    document.body.addEventListener('click',this.event);
  },
  
  unbind() {
    console.log('unbind');
    
    this.el.removeEventListener('click', this.stopProp);
    document.body.removeEventListener('click',this.event);
  },

  stopProp( event ) { 
    event.stopPropagation()
  }
});

const requireComponent = require.context(
  // The relative path of the components folder
  './modules',
  // Whether or not to look in subfolders
  true,
  // The regular expression used to match base component filenames
  /\w+\.(vue|js)$/
)

requireComponent.keys().forEach(fileName => {
  // Get component config
  const componentConfig = requireComponent(fileName)

  // Get PascalCase name of component
  const componentName = upperFirst(
    camelCase(
      // Gets the file name regardless of folder depth
      fileName
        .split('/')
        .pop()
        .replace(/\.\w+$/, '')
    )
  );

  // console.log( componentName );

  // Register component globally
  Vue.component(
    componentName,
    // Look for the component options on `.default`, which will
    // exist if the component was exported with `export default`,
    // otherwise fall back to module's root.
    componentConfig.default || componentConfig
  );
})