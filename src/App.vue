<template>
	<main>
		<CreateClient />
		<h1>Hello from clients</h1>
		<ul v-for="client in clients" :key="client.key" class="clients">
			<li>{{ client.name }}</li>
		</ul>
	</main>
</template>

<script>
import CreateClient from "./components/CreateClient";

export default {
	name: "App",
	components: {
		CreateClient,
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
.clients {
	font-weight: bold;
}
</style>
