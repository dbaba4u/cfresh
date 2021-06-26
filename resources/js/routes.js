import ExampleApp from "./components/ExampleApp";
import PaymentList from "./components/PaymentList";
import VueRouter from "vue-router";

const routes = [
    {
        path: "/admin/employees",
        component: ExampleApp,
        name: "example-app"
    },
    {
        path: "/admin/employees/payment-list",
        component: PaymentList,
        name: "payment-list"
    }
];

const router = new VueRouter({
    routes, // short for `routes: routes`
    mode: "history"
});

export default router
