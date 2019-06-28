<template>
    <div class="row">
        <div class="col-md-12">
            <h1>Calendar</h1>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Event</label>
                <input type="text" class="form-control" v-model="event_name">
            </div>
            <div class="row">
                <div class="form-group col-md-6">
                    <label>From</label>
                    <form-datepicker :element-class="'form-control'" v-model="date_from" v-on:change="fetchEvents()"></form-datepicker>
                </div>
                <div class="form-group col-md-6">
                    <label>To</label>
                    <form-datepicker :element-class="'form-control'" v-model="date_to" v-on:change="fetchEvents()"></form-datepicker>
                </div>
            </div>
            <div class="form-group">
                <template v-for="day in days">
                    <div class="custom-control custom-control-inline custom-checkbox">
                        <input type="checkbox" class="custom-control-input" :id="'day-' + day.value" v-on:click="toggleDay(day.value)">
                        <label class="custom-control-label" :for="'day-' + day.value">{{ day.day }}</label>
                    </div>
                </template>
            </div>
            <button class="btn btn-primary" v-on:click="saveEvents()">Save</button>
        </div>
        <div class="col-md-6">
            <template v-for="(days, month) in this.events">
                <h2>{{ month }}</h2>
                <div class="col-sm-12 border-top" v-for="day in days">
                    <div :class="'bg-success fade' + (day.event != '' ? ' updated' : '')" style="position: absolute; width: 100%; height: 100%; left: 0; top: 0;"></div>
                    <div class="row">
                        <div class="col-sm-3">{{ day.day }}</div>
                        <div class="col-sm-9">{{ day.event }}</div>
                    </div>
                </div>
            </template>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                base_url    : '',
                event_name  : '',
                date_from   : Vue.moment().startOf('month').format('YYYY-MM-DD'),
                date_to     : Vue.moment().endOf('month').format('YYYY-MM-DD'),
                checked_days: [],
                days: [
                    {
                        value: 1,
                        day  : 'Mon'
                    },
                    {
                        value: 2,
                        day  : 'Tue'
                    },
                    {
                        value: 3,
                        day  : 'Wed'
                    },
                    {
                        value: 4,
                        day  : 'Thur'
                    },
                    {
                        value: 5,
                        day  : 'Fri'
                    },
                    {
                        value: 6,
                        day  : 'Sat'
                    },
                    {
                        value: 0,
                        day  : 'Sun'
                    }
                ],
                events: []
            };
        },

        created() {
            this.base_url = window.base_url;

            this.fetchEvents();
        },

        methods: {
            fetchEvents() {
                var params = {
                    date_from: this.date_from,
                    date_to  : this.date_to
                };
                axios.get(base_url + '/events', {params: params}).then((response) => {
                    this.events = response.data.events;
                }).catch((error) => console.log(error.response));
            },
            saveEvents() {
                var params = {
                    name     : this.event_name,
                    date_from: this.date_from,
                    date_to  : this.date_to,
                    days     : this.checked_days
                };
                axios.post(base_url + '/events', params).then((response) => {
                    this.events = response.data.events;

                    setTimeout(function () {
                        $('.fade.updated').addClass('show');
                        setTimeout(function () {
                            $('.fade.updated').removeClass('show');
                        }, 1000);
                    }, 0);

                    toastr.success('Event successfully saved.');
                }).catch((error) => console.log(error.response));

                return false;
            },
            toggleDay(day) {
                var index = this.checked_days.indexOf(day);
                if (index >= 0) {
                    this.checked_days.splice(index, 1);
                } else {
                    this.checked_days.push(day);
                }
            }
        }
    }
</script>
