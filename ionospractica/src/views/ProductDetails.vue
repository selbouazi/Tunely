<template>
  <ion-page>
    <ion-header>
      <ion-toolbar>
        <ion-buttons slot="start">
          <ion-back-button default-href="/tabs/tab2" text="Tornar" />
        </ion-buttons>
        <ion-title>{{ product?.name || 'Producte' }}</ion-title>
      </ion-toolbar>
    </ion-header>
    <ion-content>
      <div v-if="product" class="ion-padding">
        <div class="image-wrapper">
          <img :src="product.image" :alt="product.name" class="product-image" />
        </div>

        <h1>{{ product.name }}</h1>
        <p class="product-price">{{ product.price }} €</p>
        <p><strong>Stock:</strong> {{ product.stock }} unitats</p>
        <p class="ion-margin-top">{{ product.description }}</p>
      </div>

      <div v-else class="ion-text-center ion-padding">
        <p>Carregant producte...</p>
      </div>
    </ion-content>
  </ion-page>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import {
  IonPage, IonHeader, IonToolbar, IonTitle, IonContent,
  IonButtons, IonBackButton,
} from '@ionic/vue';

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

const route = useRoute();
const product = ref<Product | null>(null);

onMounted(async () => {
  try {
    const res = await fetch(`http://127.0.0.1:8000/api/products/${route.params.id}`);
    product.value = await res.json();
  } catch (e) {
    console.error('Error loading product:', e);
  }
});
</script>

<style scoped>
.image-wrapper {
  display: flex;
  justify-content: center;
  margin-bottom: 16px;
}

.product-image {
  max-width: 100%;
  max-height: 300px;
  border-radius: 8px;
}

.product-price {
  font-size: 1.5em;
  font-weight: bold;
  color: var(--ion-color-primary);
}
</style>
