<template>
    <div>
        <input type="text" id="page_dom_element" class="form-control" placeholder="jQuery path" v-model="value.dom_jquery_path" />
        <select name="" id="" class="form-control" v-model="value.dom_contain">
            <option value="equals">equals</option>
            <option value="does-not-equals">does not equals</option>
            <option value="contains">contains</option>
            <option value="does-not-contain">does not contain</option>
            <option value="is-empty">is empty</option>
            <option value="is-not-empty">is not empty</option>
            <option value="begins-with">begins with</option>
            <option value="has-class">has Class</option>
            <option value="does-not-have-class">does not have Class</option>
            <option value="has-id">has ID</option>
            <option value="does-not-have-id">does not have ID</option>
            <option value="has-attribute">Has Attribute</option>
            <option value="does-not-have-attribute">Does not have Attribute</option>
            <option value="end-with">end with</option>
        </select>
        <input type="text" class="form-control" v-model="value.dom_value"/>
    </div>
</template>

<script>
export default {
    props : ['value'],
    computed  : {
        dom_jquery_path : {
            get () {
                return this.value.dom_jquery_path;
            }, 
            set ( dom_jquery_path ) {
                this.$emit( 'input', {
                    ...this.value, dom_jquery_path
                });
            }
        },
        dom_contain : {
            get () {
                return this.value.dom_contain;
            }, 
            set ( dom_contain ) {
                this.$emit( 'input', {
                    ...this.value, dom_contain
                });
            }
        },
        dom_value : {
            get () {
                return this.value.dom_value;
            }, 
            set ( dom_value ) {
                this.$emit( 'input', {
                    ...this.value, dom_value
                });
            }
        }
    }

}
</script>