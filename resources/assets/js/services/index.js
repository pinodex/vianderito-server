let context = require.context('./', true, /\.js$/),
    services = {}

export default function (app) {
  context.keys().forEach(name => {
    if (name === './index.js') return

    let id = name
      .replace('./', '') // Remove leading directory name
      .replace(/\//g, '.') // Replace slashes with dots
      .replace('.js', '') // Remove file ext

    let _class = context(name).default

    services[`$${id}`] = new _class(app)
  });

  return services
}
