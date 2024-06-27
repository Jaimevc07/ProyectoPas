<?php
include_once "header.php";
include_once "nav.php";
include_once "get_time_worked.php";
?>
<div class="row" id="app">
    <div class="col-12">
        <h1 class="text-center">Asistencia</h1>
    </div>
    <div class="col-12">
        <div class="form-inline mb-2">
            <label for="date">Fecha: &nbsp;</label>
            <input @change="refreshEmployeesList" v-model="date" name="date" id="date" type="date" class="form-control">

        </div>
    </div>
        <div class="col-12">
            <div class="table-responsive">
                <table class="table">
                    <thead>

                        <tr>
                            <th>Empleado</th>
                            <th>Ultima Hora de entrada</th>
                            <th>Ultima Hora de salida</th>
                            <th>Horas trabajadas</th>
                        </tr>

                    </thead>

                    <tbody>

                        <tr v-for="employee in employees">
                            <td>{{employee.name}}</td>
                            <td>{{employee.hour}}</td>
                            <td>{{employee.hour_out}}</td>
                            <td>{{employee.time_worked}}</td>
                        </tr>

                    </tbody>

                </table>
                <br>
                <br>
                <br>
                <br>

            </div>
        </div>
    </div>
    <script src="js/vue.min.js"></script>
    <script src="js/vue-toasted.min.js"></script>
    <script>
        function sumarTiempo(times) {
            let sumSeconds = 0;

            times.forEach(time => {
                let a = time.split(":");
                let seconds = +a[0] * 60 * 60 + +a[1] * 60 + +a[2];
                sumSeconds += seconds;
            });

            return new Date(sumSeconds * 1000).toISOString().substr(11, 8);
        }

        Number.prototype.pad = function(size, value) {
            var s = String(this);
            while (s.length < (size || 2)) {
                s = value + s;
            }
            return s;
        }

        Vue.use(Toasted);
        const UNSET_STATUS = "unset";
        const UNSET_HOUR = "AUSENTE";
        new Vue({
            el: "#app",
            data: () => ({
                employees: [],
                date: "",
            }),
            async mounted() {
                this.date = this.getTodaysDate();
                await this.refreshEmployeesList();
            },
            methods: {
                getTodaysDate() {
                    const date = new Date();
                    const month = date.getMonth() + 1;
                    const day = date.getDate();
                    return `${date.getFullYear()}-${(month < 10 ? '0' : '').concat(month)}-${(day < 10 ? '0' : '').concat(day)}`;
                },
                async save() {
                    // We only need id and status, nothing more
                    let employeesMapped = this.employees.map(employee => {
                        return {
                            id: employee.id,
                            status: employee.status,
                        }
                    });
                    // And we need only where status is set
                    employeesMapped = employeesMapped.filter(employee => employee.status != UNSET_STATUS);
                    const payload = {
                        date: this.date,
                        employees: employeesMapped,
                    };
                    const response = await fetch("./save_attendance_data.php", {
                        method: "POST",
                        body: JSON.stringify(payload),
                    });
                    this.$toasted.show("Saved", {
                        position: "top-left",
                        duration: 1000,
                    });
                },
                async refreshEmployeesList() {
                    // Get all employees
                    let response = await fetch("./get_employees_ajax.php");
                    let employees = await response.json();
                    // Set default status: unset
                    let employeeDictionary = {};
                    employees = employees.map((employee, index) => {
                        employeeDictionary[employee.id] = index;
                        return {
                            id: employee.id,
                            name: employee.name,
                            hour: UNSET_HOUR,
                            hour_out: UNSET_HOUR,
                            status: UNSET_STATUS,
                            hour_worked_value: 0,
                            time_worked: 'AUSENTE...',
                            hour_worked: '00:00:00',
                            session: 1
                        }
                    });
                    // Get attendance data, if any
                    response = await fetch(`./get_attendance_data_ajax.php?date=${this.date}`);
                    let attendanceData = await response.json();
                    // Refresh attendance data in each employee, if any
                    attendanceData.forEach(attendanceDetail => {
                        let employeeId = attendanceDetail.employee_id;
                        if (employeeId in employeeDictionary) {

                            let index = employeeDictionary[employeeId];
                            employees[index].status = attendanceDetail.status;
                            employees[index].hour = attendanceDetail.hour;
                            if (attendanceDetail.hour_out === '-00:00:01') {
                                employees[index].hour_out = 'Esperando salida...';
                            } else {
                                employees[index].hour_out = attendanceDetail.hour_out;
                            }
                            if (attendanceDetail.hour_out === '-00:00:01') {
                                employees[index].time_worked = 'Trabajando...';

                            } else {

                                var difference = sumarTiempo([employees[index].hour_worked, attendanceDetail.hour_worked]);
                                var tiempo1 = new Date('1970-01-01T' + difference);
                                //console.log(employees[index]);
                                employees[index].hour_worked = tiempo1.getHours().pad(2, '0') +
                                    ':' + tiempo1.getMinutes().pad(2, '0') +
                                    ':' + tiempo1.getSeconds().pad(2, '0');

                                employees[index].hour_worked_value += ((tiempo1.getHours()) +
                                    ((tiempo1.getMinutes()) / 60)).toFixed(2);

                                employees[index].time_worked = employees[index].hour_worked +
                                    '  -->  ' + ((tiempo1.getHours()) +
                                        ((tiempo1.getMinutes()) / 60)).toFixed(2) + ' Horas (en ' + (employees[index].session++) + ' sesiones)';

                            }
                        }
                    });
                    // Let Vue do its magic ;)
                    this.employees = employees;
                }
            },
        });
    </script>
    <?php
    include_once "footer.php";
