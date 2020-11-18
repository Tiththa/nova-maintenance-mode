import Tool from './components/Tool.vue';

Nova.booting((Vue, router) => {
    router.addRoutes([{
        name: 'nova-maintenance-mode',
        path: '/nova-maintenance-mode',
        component: Tool,
    }, ])
})