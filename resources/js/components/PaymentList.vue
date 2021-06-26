<template>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card" style="background-color: #EBF5FB">
                    <div class="ml-3 mt-3 mb-0 " style="font-family: 'Times New Roman'; color: #2471A3">
                        <h5 ><strong>Employees Payment's Form</strong></h5></div>
<!--                    <div class="" ><hr style="margin-top: -5px; margin-left: 10px; margin-right: 10px;"></div>-->
                    <div class="card-header" style="border: 0; margin-top:-25px"><hr ></div>

                    <div class="card-body">
                        <div class="row">
                            <!-- SEARCH FORM -->
                            <form class="">
                                <div class="form-group">
                                    <label class="sr-only" for="search_name">Name</label>
                                    <input type="text" name="search_name" class="form-control mb-2 mr-sm-2" id="search_name" placeholder="Search ...">
                                </div>
                            </form>
                            <!--/ SEARCH FORM -->
                        </div>
                        <div class="row">
                            <div style="background-color: #0d374a; width: 100%" class="text-white p-3">
                                <strong>Total Record: 0</strong>
                            </div>
                        </div>

                        <!-- SEARCH TABLE -->
                        <div class="row mt-2">
                            <table class="table table-hover table-sm">
                                <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Name</th>
                                    <th>Balance</th>
                                    <th>Amount</th>
                                    <th>Cash Type</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>12th of March</td>
                                    <td>10:00 Am</td>
                                    <td>Dauda Baba</td>
                                    <td>2000</td>
                                    <td>1000</td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <!--/ SEARCH TABLE -->
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
