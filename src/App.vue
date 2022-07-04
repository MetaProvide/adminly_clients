<template>
	<main>
		<!-- <Overview /> -->
		<ClientsTable
			:clients="clientsList"
			:is-empty="isEmpty"
			@update-clients="updateClients"
		/>
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
		return { clientsList: null, isEmpty: false };
	},
	async created() {
		this.clientsList = await ClientsUtil.fetchClients();
	},
	methods: {
		async updateClients() {
			this.clientsList = await ClientsUtil.fetchClients();
			this.isEmpty = this.clientsList.length === 0;
		},
	},
};
</script>
<style>
:root {
	--adminly-light-blue: #238dda;
	--adminly-dark-blue: #346188;
	--adminly-link-blue: #1364af;
	--adminly-light-grey: rgba(196, 196, 196, 0.1);
	--adminly-grey: rgba(0, 0, 0, 0.15);
	--adminly-line-color: #eeeeee;
	--neutral-600: #4b4b4b;
	--neutral-500: #8e8e8e;
}
</style>
<style scoped>
main {
	min-width: 80%;
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

.close-button {
	background-image: url("../img/close.svg");
	background-position: center;
	background-repeat: no-repeat;
}

.edit-button {
	background-image: url("../img/edit.svg");
	background-position: center;
	background-repeat: no-repeat;
}

.delete-button {
	background-image: url("../img/trash.svg");
	background-position: center;
	background-repeat: no-repeat;
}
</style>
