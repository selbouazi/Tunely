<template>
  <ion-page>
    <ion-header>
      <ion-toolbar>
        <ion-title>Info</ion-title>
      </ion-toolbar>
    </ion-header>
    <ion-content :fullscreen="true">
      <ion-header collapse="condense">
        <ion-toolbar>
          <ion-title size="large">Info</ion-title>
        </ion-toolbar>
      </ion-header>

      <div class="ion-padding ion-text-center">
        <h2>RA7.RA9. Pràctica 3</h2>
        <p>Desenvolupament d'una API REST amb Laravel i consum des d'una aplicació Ionic</p>
        <p class="ion-margin-top">Institut Camí de Mar · DAW · 2025-2026</p>
      </div>
    </ion-content>
  </ion-page>
</template>

<script setup lang="ts">
import { IonPage, IonHeader, IonToolbar, IonTitle, IonContent } from '@ionic/vue';
</script>
