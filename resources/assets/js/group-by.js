Array.prototype.groupBy = Array.prototype.groupBy || function(key) {
  return this.reduce(function(rv, x) {
    (rv[x[key]] = rv[x[key]] || []).push(x);
    
    return rv;
  }, {})
};