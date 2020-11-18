<template>
    <div>
        <div class="mb-6 text-center">
            <heading class="mb-6">{{ __('Maintenance Mode') }}</heading>

            <toggle-button v-model="currentlyInMaintenance" @change="toggleMaintenanceState" class="" width="100" :height="44" :speed="600"/>
        </div>

        <card  class="mb-6 py-3 px-6" v-if="currentlyInMaintenance">
            <transition name="fade">
            <div class="mb-6 flex flex-col items-center justify-center">
                <svg class="w-16 h-16 fill-current text-danger mb-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512"><path d="M320 32C196.3 32 96 132.3 96 256c0 123.76 100.3 224 224 224s224-100.24 224-224c0-123.7-100.3-224-224-224zm0 400c-97.05 0-176-78.95-176-176S222.95 80 320 80s176 78.95 176 176-78.95 176-176 176zm0-112c-17.67 0-32 14.33-32 32s14.33 32 32 32 32-14.33 32-32-14.33-32-32-32zm22.32-192h-44.64c-9.47 0-16.86 8.17-15.92 17.59l12.8 128c.82 8.18 7.7 14.41 15.92 14.41h19.04c8.22 0 15.1-6.23 15.92-14.41l12.8-128c.94-9.42-6.45-17.59-15.92-17.59zM48 256c0-59.53 19.55-117.38 55.36-164.51 5.18-6.81 4.48-16.31-2.03-21.86l-12.2-10.41c-6.91-5.9-17.62-5.06-23.15 2.15C23.32 117.02 0 185.5 0 256c0 70.47 23.32 138.96 65.96 194.62 5.53 7.21 16.23 8.05 23.15 2.16l12.19-10.4c6.51-5.55 7.21-15.04 2.04-21.86C67.55 373.37 48 315.53 48 256zM572.73 59.71c-5.58-7.18-16.29-7.95-23.17-2l-12.15 10.51c-6.47 5.6-7.1 15.09-1.88 21.87C572.04 137.47 592 195.81 592 256c0 60.23-19.96 118.57-56.46 165.95-5.22 6.78-4.59 16.27 1.88 21.87l12.15 10.5c6.87 5.95 17.59 5.18 23.17-2C616.21 396.38 640 327.31 640 256c0-71.27-23.79-140.34-67.27-196.29z"/></svg>

                <span class="font-bold">{{ __('You are currently in Maintenance Mode') }}</span>

                <div v-if="secret" class="mt-8 text-center">
                    <p>Link para ignorar Modo Manutenção:</p>
                    <br>

                    <a target="_blank" :href="bypassLink" v-text="bypassLinkText" class="font-semibold"></a>
                </div>
            </div>
            </transition>
        </card>

    </div>
</template>

<script>
import ToggleButton from 'vue-js-toggle-button/src/Button'
export default {
    components: {
        ToggleButton
    },
    mounted() {
    },
    data: function () {
        return {
            currentlyInMaintenance: Nova.config.currentlyInMaintenanceMode,
            secret: Nova.config.maintenanceModeSecret,
        }
    },
    methods:{
        enableMaintenance() {
            Nova.request().post('/nova-vendor/Davidpiesse/nova-maintenance-mode/down', {
            }).then(response => {
                console.log(response.data.message)
                document.body.classList.add('app-in-maintenance-mode');
                this.secret = response.data.secret;
                this.currentlyInMaintenance = response.data.currentlyInMaintenance
                this.$toasted.show(response.data.message, { type: 'error' })
            })
        },
        disableMaintenance() {
            Nova.request().post('/nova-vendor/Davidpiesse/nova-maintenance-mode/up').then(response => {
                console.log(response.data.message)
                document.body.classList.remove('app-in-maintenance-mode');
                this.currentlyInMaintenance = response.data.currentlyInMaintenance;
                this.$toasted.show(response.data.message, { type: 'success' })
            })
        },
        toggleMaintenanceState(e){
            if(e.value){
                this.enableMaintenance()
            }else{
                this.disableMaintenance()
            }
        }
    },

    computed: {

        bypassLink()
        {
            return Nova.config.rootUrl + '/' + this.secret;
        },

        bypassLinkText()
        {
            return this.bypassLink.replace('https://', '');
        }
    }
}
</script>

<style scoped>
    .fade-enter-active, .fade-leave-active {
    transition: opacity 0.5s;
    }
    .fade-enter, .fade-leave-to /* .fade-leave-active below version 2.1.8 */ {
    opacity: 0;
    }
</style>
