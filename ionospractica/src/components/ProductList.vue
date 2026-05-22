<template>
  <ion-list v-if="products.length > 0">
    <ion-item v-for="product in products" :key="product.id" :router-link="`/products/${product.id}`" detail>
      <ion-thumbnail slot="start">
        <img :src="product.image" :alt="product.name" />
      </ion-thumbnail>
      <ion-label>
        <h2>{{ product.name }}</h2>
        <p>{{ product.price }} €</p>
      </ion-label>
    </ion-item>
  </ion-list>

  <div v-else class="ion-text-center ion-padding">
    <p>No hi ha productes disponibles.</p>
  </div>
</template>

<script setup lang="ts">
import { IonList, IonItem, IonLabel, IonThumbnail } from '@ionic/vue';

interface Product {
  id: number;
  name: string;
  description: string;
  price: string;
  stock: number;
  image: string;
  subcategory_id: number;
  category_id: number;
}

defineProps<{
  products: Product[];
}>();
</script>
