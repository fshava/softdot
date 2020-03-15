<template>
<div class="card text-white bg-light mb-3">
    <div class="card-header"  style="background-color:black">Enrolment Reports</div>
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-sm-4">
                <div class="card bg-dark text-white">
                    <div class="card-header">ENROLMENT : TOTAL PUPILS</div>
                    <div class="card-body">
                        <p class="card-text">Get the total enrolment report for the school in pdf format</p>
                        <a href="enrolment/total" class="btn btn-primary">Get Report</a>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="card bg-dark text-white">
                    <div class="card-header">ENROLMENT : TOTAL GIRLS</div>
                    <div class="card-body">
                        <p class="card-text">Get the total girls enrolment report for the school in pdf format</p>
                        <a href="enrolment/girls" class="btn btn-primary">Get Report</a>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="card bg-dark text-white">
                    <div class="card-header">ENROLMENT : TOTAL BOYS</div>
                    <div class="card-body">
                        <p class="card-text">Get the total boys enrolment report for the school in pdf format</p>
                        <a href="enrolment/boys" class="btn btn-primary">Get Report</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="card bg-dark text-white">
                    <div class="card-header">ENROLMENT : TOTAL BOYS/GIRLS IN A CHOSEN GRADE</div>
                    <div class="card-body">
                        <p class="card-text">
                            Get the total boys/girls enrolment in a particular grade in pdf format.
                            All you have to do is select sex and grade then click the "Get Report" button
                        </p>
                        <div class="container">
                            <div class="row">
                                <div class="col col-xs-4">
                                    <select v-model="gradeSex">
                                        <option v-for="option in options" v-bind:value="option.value">
                                            {{ option.text }}
                                        </option>
                                    </select>
                                    <span>SEX: {{ gradeSex }}</span>
                                </div>
                                <div class="col col-xs-4">
                                    <select v-model="grade">
                                        <option v-for="option in grades" v-bind:value="option.value">
                                            {{ option.text }}
                                        </option>
                                        </select>
                                    <span>GRADE: {{ grade }}</span><br><br>
                                </div>
                                    <div class="col col-xs-4">
                                        <a v-bind:href="'totalSexInGrade?grade='+ grade+'&sex='+gradeSex" class="btn btn-primary">Get Report</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            gradeSex: '',
            options: [{
                    text: 'Boy',
                    value: 'B'
                },
                {
                    text: 'Girl',
                    value: 'G'
                }
            ],
            classSex: '',
            gender: [{
                    text: 'Boy',
                    value: 'B'
                },
                {
                    text: 'Girl',
                    value: 'G'
                }
            ],
            grade: '',
            grades: [{
                    text: 'EDC A',
                    value: 'ECD A'
                },
                {
                    text: 'ECD B',
                    value: 'ECD B'
                },
                {
                    text: 'GRADE 1',
                    value: '1'
                },
                {
                    text: 'GRADE 2',
                    value: '2'
                },
                {
                    text: 'GRADE 3',
                    value: '3'
                },
                {
                    text: 'GRADE 4',
                    value: '4'
                },
                {
                    text: 'GRADE 5',
                    value: '5'
                },
                {
                    text: 'GRADE 6',
                    value: '6'
                },
                {
                    text: 'GRADE 7',
                    value: '7'
                }
            ]
        }
    },
    methods: {
        totalPupils() {

        },
    }
}
</script>

<style>

</style>
