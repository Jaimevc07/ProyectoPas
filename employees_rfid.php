<?php

include_once "header.php";
include_once "nav.php";
?>
<div class="row" id="app">
    <div class="col-12">
        <h1 class="">TARJETAS RFID </h1>
    </div>
    <div class="col-12">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>
                            Empleado
                        </th>
                        <th>
                            RFID serial
                        </th>
                        <th>
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="employee in employees">
                        <td>{{employee.name}}</td>
                        <td>

<h4 v-if="employee.rfid_serial"><span class="badge badge-success"><i class="fa fa-check"></i>&nbsp;Asignado ({{employee.rfid_serial}})</span></h4>
<h4 v-else-if="employee.waitingDeletion"><span class="badge badge-warning"><i class="fa fa-clock"></i>&nbsp;Esperando... Confirmar RFID</span></h4>
<h4 v-else-if="employee.waiting"><span class="badge badge-warning"><i class="fa fa-clock"></i>&nbsp;Esperando... Registrar RFID</span></h4>
<h4 v-else><span class="badge badge-primary"><i class="fa fa-times"></i>&nbsp;No asignado</span></h4>
                        </td>
                        <td>
                <button @click="removeRfidCard(employee)" v-if="employee.rfid_serial" class="btn btn-danger">Eliminar</button>
                <button v-else-if="employee.waiting || employee.waitingDeletion" @click="cancelWaitingForPairing" class="btn btn-warning">Cancelar</button>
                <button @click="assignRfidCard(employee)" v-else class="btn btn-info">Asignar</button>
                        </td>
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
    Vue.use(Toasted);
    let shouldCheck = true;
    let oldSerial = "";
    const CHECK_PAIRING_EMPLOYEE_INTERVAL = 1000;
    new Vue({
        el: "#app",
        data: () => ({
            employees: [],
            date: "",
        }),
        async mounted() {
            await this.setReaderForReading();
            await this.refreshEmployeesList();
        },
        methods: {
            async removeRfidCard(employee) {
                // await fetch("./remove_rfid_card.php?rfid_serial=" + rfidSerial);
                // this.$toasted.show("RFID removed", {
                //     position: "top-left",
                //     duration: 1000,
                // });
                // await this.refreshEmployeesList();
                console.log(employee);
                employee.oldSerial = employee.rfid_serial;
                employee.rfid_serial = "";
                shouldCheck = true;
                const employeeId = employee.id;
                employee.waitingDeletion = true;
                console.log("REMOVE MODE ON");
                await fetch("./set_reader_for_unpairing.php?employee_id=" + employeeId);
                this.checkIfEmployeeHasJustRemovedRfid(employee);
            },
            async checkIfEmployeeHasJustRemovedRfid(employee) {
                const r = await fetch("./get_employee_rfid_serial_by_id.php?employee_id=" + employee.id);
                const serial = await r.json();
                if (!shouldCheck) {
                    employee.waitingDeletion = false;
                    employee.rfid_serial = employee.oldSerial;
                    console.log(employee);
                    return;
                }
                if (!serial) {
                    console.log(serial);
                    this.$toasted.show("¡Serial eliminado!", {
                        position: "top-left",
                        duration: 5000,
                    });
                    await this.setReaderForReading();
                    await this.refreshEmployeesList();
                } else {
                    setTimeout(() => {
                        this.checkIfEmployeeHasJustRemovedRfid(employee);
                    }, CHECK_PAIRING_EMPLOYEE_INTERVAL);
                }
            },
            async cancelWaitingForPairing() {
                shouldCheck = false;
                await this.setReaderForReading();
            },
            async setReaderForReading() {
                console.log("READ MODE ON");
                await fetch("./set_reader_for_reading.php");
            },
            async assignRfidCard(employee) {
                shouldCheck = true;
                const employeeId = employee.id;
                employee.waiting = true;
                await fetch("./set_reader_for_pairing.php?employee_id=" + employeeId);
                this.checkIfEmployeeHasJustAssignedRfid(employee);
            },
            async checkIfEmployeeHasJustAssignedRfid(employee) {
                const r = await fetch("./get_employee_rfid_serial_by_id.php?employee_id=" + employee.id);
                const serial = await r.json();
                if (!shouldCheck) {
                    employee.waiting = false;
                    return;
                }
                if (serial) {
                    console.log(serial);
                    this.$toasted.show("RFID assigned!", {
                        position: "top-left",
                        duration: 1000,
                    });
                    await this.setReaderForReading();
                    await this.refreshEmployeesList();
                } else {
                    setTimeout(() => {
                        this.checkIfEmployeeHasJustAssignedRfid(employee);
                    }, CHECK_PAIRING_EMPLOYEE_INTERVAL);
                }
            },
            async refreshEmployeesList() {
                // Get all employees
                let response = await fetch("./get_employees_ajax.php");
                let employees = await response.json();
                // Set rfid_serial by default: null
                let employeeDictionary = {};
                employees = employees.map((employee, index) => {
                    employeeDictionary[employee.id] = index;
                    return {
                        id: employee.id,
                        name: employee.name,
                        rfid_serial: null,
                        waiting: false,
                    }
                });
                // Get RFID data, if any
                response = await fetch(`./get_employees_with_rfid.php`);
                let rfidData = await response.json();
                // Refresh rfid data in each employee, if any
                rfidData.forEach(rfidDetail => {
                    let employeeId = rfidDetail.employee_id;
                    if (employeeId in employeeDictionary) {
                        let index = employeeDictionary[employeeId];
                        employees[index].rfid_serial = rfidDetail.rfid_serial;
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
