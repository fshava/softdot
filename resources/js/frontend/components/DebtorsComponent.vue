<template>
<div class="card text-white bg-light mb-3">
    <div class="card-header" style="background-color:black">Debtors Reports</div>
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-sm-6">
                <div class="card bg-success text-white">
                    <div class="card-header">DEBTORS : INDIVIDUAL INVOICE</div>
                    <div class="card-body">
                        <p class="card-text">Get the invoice of a single pupil in pdf format.
                            Please search for the pupil you want to generate an invoice for and click "get report" button.
                        </p>
                        <div class="control">
                            <input @keyup="searchInvoice" type="text" class="input is-small" placeholder="search" v-model="search">
                        </div>
                            <table class="table is-striped is-hoverable">
                                <thead>
                                    <tr>
                                        <!-- <th>#</th> -->
                                        <th>name</th>
                                        <th>surname</th>
                                        <th>grade</th>
                                        <th>class</th>
                                        <th>D.O.B</th>
                                        <th>PDF</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(pupil,index,key) in onePupil" :key="key">
                                        <!-- <td>{{ index }}</td> -->
                                        <td>{{ pupil.name }}</td>
                                        <td>{{ pupil.surname }}</td>
                                        <td>{{ pupil.grade }}</td>
                                        <td>{{ pupil.class }}</td>
                                        <td>{{ pupil.dob }}</td>
                                        <td><a v-bind:href="'singleDebtor?id='+ pupil.id" class="btn btn-primary">PDF</a></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="card bg-success text-white">
                        <div class="card-header">DEBTORS : DEBTORS FOR A SINGLE FEE ITEM</div>
                        <div class="card-body">
                            <p class="card-text">Get debtors for a particular fee item. search the fee in the search box bellow and select the pdf button</p>
                            <div class="control">
                                <input @keyup="searchProduct" type="text" class="input is-small" placeholder="search" v-model="searchPro">
                        </div>
                                <table class="table is-striped is-hoverable">
                                    <thead>
                                        <tr>
                                            <th>category</th>
                                            <th>description</th>
                                            <th>term</th>
                                            <th>year</th>
                                            <th>amount</th>
                                            <th>PDF</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(stat,index,key) in oneProduct" :key="key">
                                            <td>{{ stat.category }}</td>
                                            <td>{{ stat.description }}</td>
                                            <td>{{ stat.term }}</td>
                                            <td>{{ stat.year }}</td>
                                            <td>{{ stat.amount }}</td>
                                            <td><a v-bind:href="'debtorsForOneItem?id='+ stat.id" class="btn btn-primary">PDF</a></td>
                                        </tr>
                                    </tbody>
                                </table>
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
            onePupil: [],
            search: '',
            searchPro: '',
            oneProduct: []
        }
    },
    methods: {
        searchInvoice() {
            console.log(this.search);
            fetch(`api/pupils`, {
                    method: 'post',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        'search': this.search,
                    })
                })
                .then(res => res.json())
                .then(data => {
                    console.log(data);
                    this.onePupil = data.data;
                })
                .catch(err => {
                    console.log(err);
                })
        },
        searchProduct() {
            console.log(this.search);
            fetch(`api/products`, {
                    method: 'post',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        'search': this.searchPro,
                    })
                })
                .then(res => res.json())
                .then(data => {
                    console.log(data);
                    this.oneProduct = data.data;
                })
                .catch(err => {
                    console.log(err);
                })
        },
    }
}
</script>

<style>

</style>
