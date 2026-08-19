<x-admin::layouts>
    <v-test></v-test>

    @pushOnce('styles')
    @vite(['resources/js/realtime-supabase.js'])
    @endPushOnce

    @pushOnce('scripts')

    <script
        type="text/x-template"
        id="v-test-template"
    >
        <div>
            <h3>Supabase Realtime Test</h3>

            <p>
                Status:
                <strong>@{{ connectionStatus }}</strong>
            </p>

            <button @click="sendTestMessage">
                Send Test Message
            </button>

            <div v-if="messages.length">
                <h4>Messages</h4>

                <ul>
                    <li v-for="message in messages" :key="message.id">
                        @{{ message.message }}
                    </li>
                </ul>
            </div>
        </div>
    </script>

    <script type="module">  
        adminVueApp.component('v-test', {
            template: '#v-test-template',

            data() {
                return {
                    show: false,

                    connectionStatus: 'Connecting...',

                    messages: [],

                    channel: null,
                };
            },

            mounted() {
                this.connectRealtime();
            },

            beforeUnmount() {
                this.disconnectRealtime();
            },

            methods: {

                connectRealtime() {

                    this.channel = supabase
                        .channel('test-websocket')

                        .on(
                            'broadcast',
                            {
                                event: 'test-message',
                            },
                            (payload) => {

                                console.log(
                                    'Received:',
                                    payload
                                );

                                this.messages.push({
                                    id: Date.now(),
                                    message: payload.payload.message,
                                });

                            }
                        )

                        .subscribe((status) => {

                            console.log(
                                'Realtime status:',
                                status
                            );

                            this.connectionStatus = status;

                        });
                },

                sendTestMessage() { 
                    if (!this.channel) {
                        return;
                    }

                    console.log('channel created successfully');

                    this.channel.send({
                        type: 'broadcast',
                        event: 'test-message',

                        payload: {
                            message: 'Hello from Laravel Admin!',
                            time: new Date().toLocaleTimeString(),
                        },
                    });

                },

                disconnectRealtime() {

                    if (this.channel) {

                        supabase.removeChannel(
                            this.channel
                        );

                        this.channel = null;

                    }

                },

            },

        });
    </script>

    @endPushOnce
</x-admin::layouts>