<template>
    <input v-datepicker type="text" :class="elementClass" :value="value" :data-field="dataField" :data-format="dataDateFormat" :data-moment-format="dataMomentFormat" :data-min-date="min" :data-max-date="max">
</template>

<script>
    export default {
        props: {
            elementClass  : String,
            value         : String,
            dataDateFormat: {
                default: 'yy-mm-dd',
                type   : String
            },
            dataMomentFormat: {
                default: 'YYYY-MM-DD',
                type   : String
            },
            min: {
                default: null,
                type   : String
            },
            max: {
                default: null,
                type   : String
            },
            dataField: String
        },
        directives: {
            datepicker: {
                inserted (el, binding, vNode) {
                    var interval = setInterval(function (el, binding, vNode) {
                        if (typeof $().datepicker === 'function' && $(el).is(':visible')) {
                            $(el).datepicker({
                                autoclose : true,
                                minDate   : $(el).data('min-date'),
                                maxDate   : $(el).data('max-date'),
                                dateFormat: $(el).data('format'),
                                onSelect: function(dateText) {
                                    vNode.context.$emit('input', dateText);
                                    vNode.context.$emit('change');
                                },
                                onClose: function(dateText) {
                                    var moment_format = $(el).data('moment-format');

                                    if (dateText != '') {
                                        var date = Vue.moment(dateText, moment_format);
                                        if (!date.isValid()) {
                                            date = Vue.moment();

                                            vNode.context.$emit('input', date.format(moment_format));
                                            vNode.context.$emit('change');

                                            $(el).val(date.format(moment_format));

                                            var d = new Date(date.get('year'), date.get('month'), date.get('date'));
                                            $(el).datepicker('setDate', d);
                                        } else {
                                            vNode.context.$emit('input', date.format(moment_format));

                                            $(el).val(date.format(moment_format));
                                        }
                                    } else {
                                        date = Vue.moment();

                                        vNode.context.$emit('input', date.format(moment_format));
                                        vNode.context.$emit('change');

                                        $(el).val(date.format(moment_format));

                                        var d = new Date(date.get('year'), date.get('month'), date.get('date'));
                                        $(el).datepicker('setDate', d);
                                    }
                                }
                            });

                            clearInterval(interval);
                        }
                    }, 1000, el, binding, vNode);
                }
            }
        }
    }
</script>
