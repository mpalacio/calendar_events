<template>
    <div class="row">
        <div class="col-md-12">
            <h1>Calendar</h1>
        </div>
        <div class="col-md-3">

        </div>
        <div class="col-md-9">

        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                base_url : '',
                date_from: Vue.moment().startOf('year').format('MM-DD-YYYY'),
                date_to  : Vue.moment().endOf('year').format('MM-DD-YYYY'),
                events   : []
            };
        },

        created() {
            this.base_url = window.base_url;
        },

        methods: {

        }
    }
</script>
