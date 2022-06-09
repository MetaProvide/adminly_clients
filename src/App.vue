<template>
	<main>
		<!-- <Overview /> -->
		<ClientsTable :clients="clientsList" @update-clients="updateClients" />
	</main>
</template>

<script>
import ClientsTable from "./components/ClientsTable";
import { ClientsUtil } from "./utils";
// import Overview from "./components/Overview";

export default {
	name: "App",
	components: {
		// Overview,
		ClientsTable,
	},
	data() {
		return { clientsList: null };
	},
	async created() {
		this.clientsList = await ClientsUtil.fetchClients();
	},
	methods: {
		async updateClients() {
			this.clientsList = await ClientsUtil.fetchClients();
		},
	},
};
</script>
<style>
:root {
	--adminly-light-blue: #238dda;
	--adminly-dark-blue: #346188;
	--adminly-link-blue: #1364af;
	--neutral-600: #4b4b4b;
	--neutral-500: #8e8e8e;
	--adminly-light-grey: rgba(196, 196, 196, 0.1);
	--adminly-grey: rgba(0, 0, 0, 0.15);
}
</style>
<style scoped>
main {
	width: 80%;
	margin-inline: auto;
	margin-block-end: auto;
	padding: 20px;
	display: flex;
	gap: 20px;
	flex-direction: column;
	align-content: center;
}
</style>
<style>
.app-adminly_clients {
	background-repeat: no-repeat;
	background-position: center bottom;
	background-size: contain;
	background-image: url("../img/clientsBackground.svg");
}
</style>
