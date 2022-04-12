<template>
	<main>
		<h1>Hello from clients</h1>
		<span>{{ clients.name }}</span>
	</main>
</template>

<script>
export default {
	name: "App",
	props: {
		clients: {
			type: Object,
			default() {
				return {};
			},
		},
	},
};
</script>

<style scoped>
.client {
	font-weight: bold;
}
</style>
