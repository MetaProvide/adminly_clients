<template>
	<main>
		<div class="tableContent">
			<ClientsTable :clients="clients" />
		</div>
	</main>
</template>

<script>
import ClientsTable from "./components/ClientsTable.vue";
export default {
	name: "App",
	components: {
		ClientsTable,
	},
	props: {
		clients: {
			type: Array,
			default() {
				return [];
			},
		},
	},
};
</script>

<style scoped>
main {
	width: 100%;
	min-height: 100vh;
	padding: 20px;
	display: flex;
	justify-content: center;
	gap: 20px;
}

.clients {
	font-weight: bold;
}
</style>

<style scoped>
.tableContent {
	display: flex;
	justify-content: center;
}
</style>
