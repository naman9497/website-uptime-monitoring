import pluginVue from 'eslint-plugin-vue'
import vuePrettier from '@vue/eslint-config-prettier'
import pluginVitest from '@vitest/eslint-plugin'

export default [
  {
    name: 'app/files-to-lint',
    files: ['**/*.{js,mjs,jsx,vue}'],
  },

  {
    name: 'app/files-to-ignore',
    ignores: ['**/dist/**', '**/dist-ssr/**', '**/coverage/**'],
  },

  ...pluginVue.configs['flat/essential'],

  {
    ...pluginVitest.configs.recommended,
    files: ['src/**/__tests__/*'],
  },

  vuePrettier,
]
