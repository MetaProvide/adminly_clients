<template>
	<div class="card-overview">
		<CardOverview :show-data="true">
			<template #title> Total Earnings</template>
		</CardOverview>
		<CardOverview :show-data="true">
			<template #title> Sessions</template>
		</CardOverview>
		<CardOverview :show-data="true">
			<template #title>Total Hours</template>
		</CardOverview>
		<CardOverview :show-data="true">
			<template #title>Total Hours</template>
		</CardOverview>
	</div>
</template>

<script>
import CardOverview from "./CardOverview";

export default {
	name: "Overview",
	components: {
		CardOverview,
	},
};
</script>

<style scoped>
.card-overview {
	display: flex;
	width: 80%;
	margin: auto;
	padding: 20px;
}
</style>
