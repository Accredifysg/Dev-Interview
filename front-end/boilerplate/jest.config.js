module.exports = {
  preset: '@vue/cli-plugin-unit-jest',
  collectCoverage: true,
  collectCoverageFrom: [
    'src/**/*.{js,vue}',
    '!src/i18n.js',
    '!src/main.js',
  ],
  transform: {
    '^.+\\.vue$': 'vue-jest',
  },
};
