// @ts-check
import { defineConfig } from 'astro/config';
import react from '@astrojs/react';
import node from '@astrojs/node';
// https://astro.build/config
export default defineConfig({
  integrations: [react()],
  output: 'server',
  adapter: node({
    mode: 'standalone',
  }),
  //Open Network
  // server:
  // {
  //   host: true,
  //   open: true
  // }
  //Setup xss
  // experimental:({
  //   csp:true
  // }),
});