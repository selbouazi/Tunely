<template>
  <ion-page>
    <ion-header>
      <ion-toolbar>
        <ion-title>Productes</ion-title>
      </ion-toolbar>
    </ion-header>
    <ion-content :fullscreen="true">
      <ion-header collapse="condense">
        <ion-toolbar>
          <ion-title size="large">Productes</ion-title>
        </ion-toolbar>
      </ion-header>

      <ProductList :products="products" />
    </ion-content>
  </ion-page>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { IonPage, IonHeader, IonToolbar, IonTitle, IonContent } from '@ionic/vue';
import ProductList from '@/components/ProductList.vue';

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

const products = ref<Product[]>([]);

onMounted(async () => {
  try {
    const res = await fetch('http://127.0.0.1:8000/api/products');
    const data: Product[] = await res.json();
    data.sort((a, b) => a.name.localeCompare(b.name));
    products.value = data;
  } catch (e) {
    console.error('Error loading products:', e);
  }
});
</script>
