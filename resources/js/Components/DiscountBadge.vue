<script setup>
import { defineProps } from 'vue';

defineProps(['jumlah'])
</script>

<template>
    <div class="discount-badge">
      <div class="circle">
        <div class="text">{{jumlah}}% DISCOUNT</div>
      </div>
    </div>
  </template>

  <script>
  export default {
    name: "DiscountBadge",
  };
  </script>

  <style scoped>
  .discount-badge {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 150px;
    height: 150px;
    border-radius: 50%;
    background-color: #a80000;
    color: white;
    text-align: center;
    font-weight: bold;
    font-family: Arial, sans-serif;
    position: relative;
    overflow: hidden;
  }

  .circle {
    width: 80%;
    height: 80%;
    border: 2px solid white;
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
    position: relative;
    transform: rotate(10deg);
  }

  .text {
    transform: rotate(-10deg);
    font-size: 1.2em;
    padding: 0 10px;
  }

  .discount-badge::before,
  .discount-badge::after {
    position: absolute;
    font-size: 1em;
    opacity: 0.7;
    color: white;
    top: 10px;
    left: 50%;
    transform: translateX(-50%) rotate(-10deg);
  }

  .discount-badge::after {
    top: auto;
    bottom: 10px;
  }
  </style>
