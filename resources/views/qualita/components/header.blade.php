<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1"> 
      <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>


      <title><?php echo isset($titolo) ? $titolo : 'Arca Industry' ?></title>
    <link rel="icon" href="<?php echo URL::asset('img/ico.png') ?>">


 <style>
 
*,
*::before,
*::after {
  box-sizing: border-box;
}

html {
  font-family: sans-serif;
  line-height: 1.15;
  -webkit-text-size-adjust: 100%;
  -webkit-tap-highlight-color: rgba(0, 0, 0, 0);
}

article, aside, figcaption, figure, footer, header, hgroup, main, nav, section {
  display: block;
}

body {
  margin: 0;
  font-family: "Source Sans Pro", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol";
  font-size: 1rem;
  font-weight: 400;
  line-height: 1.5;
  color: #212529;
  text-align: left;
  background-color: #fff;
}

[tabindex="-1"]:focus:not(:focus-visible) {
  outline: 0 !important;
}
 
p {
  margin-top: 0;
  margin-bottom: 1rem;
}
 
ol,
ul,
dl {
  margin-top: 0;
  margin-bottom: 1rem;
}

ol ol,
ul ul,
ol ul,
ul ol {
  margin-bottom: 0;
}

dt {
  font-weight: 700;
}
 
img {
  vertical-align: middle;
  border-style: none;
}
 

table {
  border-collapse: collapse;
}
 

th {
  text-align: inherit;
  text-align: -webkit-match-parent;
}

 

button {
  border-radius: 0;
}

button:focus {
  outline: 1px dotted;
  outline: 5px auto -webkit-focus-ring-color;
}

input,
button,
select,
optgroup,
textarea {
  margin: 0;
  font-family: inherit;
  font-size: inherit;
  line-height: inherit;
}

button,
input {
  overflow: visible;
}

button,
select {
  text-transform: none;
}

[role="button"] {
  cursor: pointer;
}

select {
  word-wrap: normal;
}

button,
[type="button"],
[type="reset"],
[type="submit"] {
  -webkit-appearance: button;
}

button:not(:disabled),
[type="button"]:not(:disabled),
[type="reset"]:not(:disabled),
[type="submit"]:not(:disabled) {
  cursor: pointer;
}

button::-moz-focus-inner,
[type="button"]::-moz-focus-inner,
[type="reset"]::-moz-focus-inner,
[type="submit"]::-moz-focus-inner {
  padding: 0;
  border-style: none;
}

input[type="radio"],
input[type="checkbox"] {
  box-sizing: border-box;
  padding: 0;
}

textarea {
  overflow: auto;
  resize: vertical;
}

fieldset {
  min-width: 0;
  padding: 0;
  margin: 0;
  border: 0;
}

legend {
  display: block;
  width: 100%;
  max-width: 100%;
  padding: 0;
  margin-bottom: .5rem;
  font-size: 1.5rem;
  line-height: inherit;
  color: inherit;
  white-space: normal;
}

progress {
  vertical-align: baseline;
}

[type="number"]::-webkit-inner-spin-button,
[type="number"]::-webkit-outer-spin-button {
  height: auto;
}

[type="search"] {
  outline-offset: -2px;
  -webkit-appearance: none;
}

[type="search"]::-webkit-search-decoration {
  -webkit-appearance: none;
}

::-webkit-file-upload-button {
  font: inherit;
  -webkit-appearance: button;
}

output {
  display: inline-block;
}

summary {
  display: list-item;
  cursor: pointer;
}

template {
  display: none;
}

[hidden] {
  display: none !important;
}

h1, h2, h3, h4, h5, h6,
.h1, .h2, .h3, .h4, .h5, .h6 {
  margin-bottom: 0.5rem;
  font-family: inherit;
  font-weight: 500;
  line-height: 1.2;
  color: inherit;
}

h1, .h1 {
  font-size: 2.5rem;
}

h2, .h2 {
  font-size: 2rem;
}

h3, .h3 {
  font-size: 1.75rem;
}

h4, .h4 {
  font-size: 1.5rem;
}

h5, .h5 {
  font-size: 1.25rem;
}

h6, .h6 {
  font-size: 1rem;
}

.lead {
  font-size: 1.25rem;
  font-weight: 300;
}

.display-1 {
  font-size: 6rem;
  font-weight: 300;
  line-height: 1.2;
}

.display-2 {
  font-size: 5.5rem;
  font-weight: 300;
  line-height: 1.2;
}

.display-3 {
  font-size: 4.5rem;
  font-weight: 300;
  line-height: 1.2;
}

.display-4 {
  font-size: 3.5rem;
  font-weight: 300;
  line-height: 1.2;
}

hr {
  margin-top: 1rem;
  margin-bottom: 1rem;
  border: 0;
  border-top: 1px solid rgba(0, 0, 0, 0.1);
}

small,
.small {
  font-size: 80%;
  font-weight: 400;
}

mark,
.mark {
  padding: 0.2em;
  background-color: #fcf8e3;
}

.list-unstyled {
  padding-left: 0;
  list-style: none;
}

.list-inline {
  padding-left: 0;
  list-style: none;
}

.list-inline-item {
  display: inline-block;
}

.list-inline-item:not(:last-child) {
  margin-right: 0.5rem;
}

.initialism {
  font-size: 90%;
  text-transform: uppercase;
}

.blockquote {
  margin-bottom: 1rem;
  font-size: 1.25rem;
}

.blockquote-footer {
  display: block;
  font-size: 80%;
  color: #6c757d;
}

.blockquote-footer::before {
  content: "\2014\00A0";
}

.img-fluid {
  max-width: 100%;
  height: auto;
}

.img-thumbnail {
  padding: 0.25rem;
  background-color: #fff;
  border: 1px solid #dee2e6;
  border-radius: 0.25rem;
  box-shadow: 0 1px 2px rgba(0, 0, 0, 0.075);
  max-width: 100%;
  height: auto;
}

.figure {
  display: inline-block;
}

.figure-img {
  margin-bottom: 0.5rem;
  line-height: 1;
}

.figure-caption {
  font-size: 90%;
  color: #6c757d;
}

code {
  font-size: 87.5%;
  color: #e83e8c;
  word-wrap: break-word;
}

a > code {
  color: inherit;
}

kbd {
  padding: 0.2rem 0.4rem;
  font-size: 87.5%;
  color: #fff;
  background-color: #212529;
  border-radius: 0.2rem;
  box-shadow: inset 0 -0.1rem 0 rgba(0, 0, 0, 0.25);
}

kbd kbd {
  padding: 0;
  font-size: 100%;
  font-weight: 700;
  box-shadow: none;
}

pre {
  display: block;
  font-size: 87.5%;
  color: #212529;
}

pre code {
  font-size: inherit;
  color: inherit;
  word-break: normal;
}

.pre-scrollable {
  max-height: 340px;
  overflow-y: scroll;
}

.container,
.container-fluid,
.container-sm,
.container-md,
.container-lg,
.container-xl {
  width: 100%;
  padding-right: 7.5px;
  padding-left: 7.5px;
  margin-right: auto;
  margin-left: auto;
}

@media (min-width: 576px) {
  .container, .container-sm {
    max-width: 540px;
  }
}

@media (min-width: 768px) {
  .container, .container-sm, .container-md {
    max-width: 720px;
  }
}

@media (min-width: 992px) {
  .container, .container-sm, .container-md, .container-lg {
    max-width: 960px;
  }
}

@media (min-width: 1200px) {
  .container, .container-sm, .container-md, .container-lg, .container-xl {
    max-width: 1140px;
  }
}

.row {
  display: -ms-flexbox;
  display: flex;
  -ms-flex-wrap: wrap;
  flex-wrap: wrap;
  margin-right: -7.5px;
  margin-left: -7.5px;
}

.no-gutters {
  margin-right: 0;
  margin-left: 0;
}

.no-gutters > .col,
.no-gutters > [class*="col-"] {
  padding-right: 0;
  padding-left: 0;
}

.col-1, .col-2, .col-3, .col-4, .col-5, .col-6, .col-7, .col-8, .col-9, .col-10, .col-11, .col-12, .col,
.col-auto, .col-sm-1, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6, .col-sm-7, .col-sm-8, .col-sm-9, .col-sm-10, .col-sm-11, .col-sm-12, .col-sm,
.col-sm-auto, .col-md-1, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-md-10, .col-md-11, .col-md-12, .col-md,
.col-md-auto, .col-lg-1, .col-lg-2, .col-lg-3, .col-lg-4, .col-lg-5, .col-lg-6, .col-lg-7, .col-lg-8, .col-lg-9, .col-lg-10, .col-lg-11, .col-lg-12, .col-lg,
.col-lg-auto, .col-xl-1, .col-xl-2, .col-xl-3, .col-xl-4, .col-xl-5, .col-xl-6, .col-xl-7, .col-xl-8, .col-xl-9, .col-xl-10, .col-xl-11, .col-xl-12, .col-xl,
.col-xl-auto {
  position: relative;
  width: 100%;
  padding-right: 7.5px;
  padding-left: 7.5px;
}

.col {
  -ms-flex-preferred-size: 0;
  flex-basis: 0;
  -ms-flex-positive: 1;
  flex-grow: 1;
  max-width: 100%;
}

.row-cols-1 > * {
  -ms-flex: 0 0 100%;
  flex: 0 0 100%;
  max-width: 100%;
}

.row-cols-2 > * {
  -ms-flex: 0 0 50%;
  flex: 0 0 50%;
  max-width: 50%;
}

.row-cols-3 > * {
  -ms-flex: 0 0 33.333333%;
  flex: 0 0 33.333333%;
  max-width: 33.333333%;
}

.row-cols-4 > * {
  -ms-flex: 0 0 25%;
  flex: 0 0 25%;
  max-width: 25%;
}

.row-cols-5 > * {
  -ms-flex: 0 0 20%;
  flex: 0 0 20%;
  max-width: 20%;
}

.row-cols-6 > * {
  -ms-flex: 0 0 16.666667%;
  flex: 0 0 16.666667%;
  max-width: 16.666667%;
}

.col-auto {
  -ms-flex: 0 0 auto;
  flex: 0 0 auto;
  width: auto;
  max-width: 100%;
}

.col-1 {
  -ms-flex: 0 0 8.333333%;
  flex: 0 0 8.333333%;
  max-width: 8.333333%;
}

.col-2 {
  -ms-flex: 0 0 16.666667%;
  flex: 0 0 16.666667%;
  max-width: 16.666667%;
}

.col-3 {
  -ms-flex: 0 0 25%;
  flex: 0 0 25%;
  max-width: 25%;
}

.col-4 {
  -ms-flex: 0 0 33.333333%;
  flex: 0 0 33.333333%;
  max-width: 33.333333%;
}

.col-5 {
  -ms-flex: 0 0 41.666667%;
  flex: 0 0 41.666667%;
  max-width: 41.666667%;
}

.col-6 {
  -ms-flex: 0 0 50%;
  flex: 0 0 50%;
  max-width: 50%;
}

.col-7 {
  -ms-flex: 0 0 58.333333%;
  flex: 0 0 58.333333%;
  max-width: 58.333333%;
}

.col-8 {
  -ms-flex: 0 0 66.666667%;
  flex: 0 0 66.666667%;
  max-width: 66.666667%;
}

.col-9 {
  -ms-flex: 0 0 75%;
  flex: 0 0 75%;
  max-width: 75%;
}

.col-10 {
  -ms-flex: 0 0 83.333333%;
  flex: 0 0 83.333333%;
  max-width: 83.333333%;
}

.col-11 {
  -ms-flex: 0 0 91.666667%;
  flex: 0 0 91.666667%;
  max-width: 91.666667%;
}

.col-12 {
  -ms-flex: 0 0 100%;
  flex: 0 0 100%;
  max-width: 100%;
}

.order-first {
  -ms-flex-order: -1;
  order: -1;
}

.order-last {
  -ms-flex-order: 13;
  order: 13;
}

.order-0 {
  -ms-flex-order: 0;
  order: 0;
}

.order-1 {
  -ms-flex-order: 1;
  order: 1;
}

.order-2 {
  -ms-flex-order: 2;
  order: 2;
}

.order-3 {
  -ms-flex-order: 3;
  order: 3;
}

.order-4 {
  -ms-flex-order: 4;
  order: 4;
}

.order-5 {
  -ms-flex-order: 5;
  order: 5;
}

.order-6 {
  -ms-flex-order: 6;
  order: 6;
}

.order-7 {
  -ms-flex-order: 7;
  order: 7;
}

.order-8 {
  -ms-flex-order: 8;
  order: 8;
}

.order-9 {
  -ms-flex-order: 9;
  order: 9;
}

.order-10 {
  -ms-flex-order: 10;
  order: 10;
}

.order-11 {
  -ms-flex-order: 11;
  order: 11;
}

.order-12 {
  -ms-flex-order: 12;
  order: 12;
}

.offset-1 {
  margin-left: 8.333333%;
}

.offset-2 {
  margin-left: 16.666667%;
}

.offset-3 {
  margin-left: 25%;
}

.offset-4 {
  margin-left: 33.333333%;
}

.offset-5 {
  margin-left: 41.666667%;
}

.offset-6 {
  margin-left: 50%;
}

.offset-7 {
  margin-left: 58.333333%;
}

.offset-8 {
  margin-left: 66.666667%;
}

.offset-9 {
  margin-left: 75%;
}

.offset-10 {
  margin-left: 83.333333%;
}

.offset-11 {
  margin-left: 91.666667%;
}

@media (min-width: 576px) {
  .col-sm {
    -ms-flex-preferred-size: 0;
    flex-basis: 0;
    -ms-flex-positive: 1;
    flex-grow: 1;
    max-width: 100%;
  }
  .row-cols-sm-1 > * {
    -ms-flex: 0 0 100%;
    flex: 0 0 100%;
    max-width: 100%;
  }
  .row-cols-sm-2 > * {
    -ms-flex: 0 0 50%;
    flex: 0 0 50%;
    max-width: 50%;
  }
  .row-cols-sm-3 > * {
    -ms-flex: 0 0 33.333333%;
    flex: 0 0 33.333333%;
    max-width: 33.333333%;
  }
  .row-cols-sm-4 > * {
    -ms-flex: 0 0 25%;
    flex: 0 0 25%;
    max-width: 25%;
  }
  .row-cols-sm-5 > * {
    -ms-flex: 0 0 20%;
    flex: 0 0 20%;
    max-width: 20%;
  }
  .row-cols-sm-6 > * {
    -ms-flex: 0 0 16.666667%;
    flex: 0 0 16.666667%;
    max-width: 16.666667%;
  }
  .col-sm-auto {
    -ms-flex: 0 0 auto;
    flex: 0 0 auto;
    width: auto;
    max-width: 100%;
  }
  .col-sm-1 {
    -ms-flex: 0 0 8.333333%;
    flex: 0 0 8.333333%;
    max-width: 8.333333%;
  }
  .col-sm-2 {
    -ms-flex: 0 0 16.666667%;
    flex: 0 0 16.666667%;
    max-width: 16.666667%;
  }
  .col-sm-3 {
    -ms-flex: 0 0 25%;
    flex: 0 0 25%;
    max-width: 25%;
  }
  .col-sm-4 {
    -ms-flex: 0 0 33.333333%;
    flex: 0 0 33.333333%;
    max-width: 33.333333%;
  }
  .col-sm-5 {
    -ms-flex: 0 0 41.666667%;
    flex: 0 0 41.666667%;
    max-width: 41.666667%;
  }
  .col-sm-6 {
    -ms-flex: 0 0 50%;
    flex: 0 0 50%;
    max-width: 50%;
  }
  .col-sm-7 {
    -ms-flex: 0 0 58.333333%;
    flex: 0 0 58.333333%;
    max-width: 58.333333%;
  }
  .col-sm-8 {
    -ms-flex: 0 0 66.666667%;
    flex: 0 0 66.666667%;
    max-width: 66.666667%;
  }
  .col-sm-9 {
    -ms-flex: 0 0 75%;
    flex: 0 0 75%;
    max-width: 75%;
  }
  .col-sm-10 {
    -ms-flex: 0 0 83.333333%;
    flex: 0 0 83.333333%;
    max-width: 83.333333%;
  }
  .col-sm-11 {
    -ms-flex: 0 0 91.666667%;
    flex: 0 0 91.666667%;
    max-width: 91.666667%;
  }
  .col-sm-12 {
    -ms-flex: 0 0 100%;
    flex: 0 0 100%;
    max-width: 100%;
  }
  .order-sm-first {
    -ms-flex-order: -1;
    order: -1;
  }
  .order-sm-last {
    -ms-flex-order: 13;
    order: 13;
  }
  .order-sm-0 {
    -ms-flex-order: 0;
    order: 0;
  }
  .order-sm-1 {
    -ms-flex-order: 1;
    order: 1;
  }
  .order-sm-2 {
    -ms-flex-order: 2;
    order: 2;
  }
  .order-sm-3 {
    -ms-flex-order: 3;
    order: 3;
  }
  .order-sm-4 {
    -ms-flex-order: 4;
    order: 4;
  }
  .order-sm-5 {
    -ms-flex-order: 5;
    order: 5;
  }
  .order-sm-6 {
    -ms-flex-order: 6;
    order: 6;
  }
  .order-sm-7 {
    -ms-flex-order: 7;
    order: 7;
  }
  .order-sm-8 {
    -ms-flex-order: 8;
    order: 8;
  }
  .order-sm-9 {
    -ms-flex-order: 9;
    order: 9;
  }
  .order-sm-10 {
    -ms-flex-order: 10;
    order: 10;
  }
  .order-sm-11 {
    -ms-flex-order: 11;
    order: 11;
  }
  .order-sm-12 {
    -ms-flex-order: 12;
    order: 12;
  }
  .offset-sm-0 {
    margin-left: 0;
  }
  .offset-sm-1 {
    margin-left: 8.333333%;
  }
  .offset-sm-2 {
    margin-left: 16.666667%;
  }
  .offset-sm-3 {
    margin-left: 25%;
  }
  .offset-sm-4 {
    margin-left: 33.333333%;
  }
  .offset-sm-5 {
    margin-left: 41.666667%;
  }
  .offset-sm-6 {
    margin-left: 50%;
  }
  .offset-sm-7 {
    margin-left: 58.333333%;
  }
  .offset-sm-8 {
    margin-left: 66.666667%;
  }
  .offset-sm-9 {
    margin-left: 75%;
  }
  .offset-sm-10 {
    margin-left: 83.333333%;
  }
  .offset-sm-11 {
    margin-left: 91.666667%;
  }
}

@media (min-width: 768px) {
  .col-md {
    -ms-flex-preferred-size: 0;
    flex-basis: 0;
    -ms-flex-positive: 1;
    flex-grow: 1;
    max-width: 100%;
  }
  .row-cols-md-1 > * {
    -ms-flex: 0 0 100%;
    flex: 0 0 100%;
    max-width: 100%;
  }
  .row-cols-md-2 > * {
    -ms-flex: 0 0 50%;
    flex: 0 0 50%;
    max-width: 50%;
  }
  .row-cols-md-3 > * {
    -ms-flex: 0 0 33.333333%;
    flex: 0 0 33.333333%;
    max-width: 33.333333%;
  }
  .row-cols-md-4 > * {
    -ms-flex: 0 0 25%;
    flex: 0 0 25%;
    max-width: 25%;
  }
  .row-cols-md-5 > * {
    -ms-flex: 0 0 20%;
    flex: 0 0 20%;
    max-width: 20%;
  }
  .row-cols-md-6 > * {
    -ms-flex: 0 0 16.666667%;
    flex: 0 0 16.666667%;
    max-width: 16.666667%;
  }
  .col-md-auto {
    -ms-flex: 0 0 auto;
    flex: 0 0 auto;
    width: auto;
    max-width: 100%;
  }
  .col-md-1 {
    -ms-flex: 0 0 8.333333%;
    flex: 0 0 8.333333%;
    max-width: 8.333333%;
  }
  .col-md-2 {
    -ms-flex: 0 0 16.666667%;
    flex: 0 0 16.666667%;
    max-width: 16.666667%;
  }
  .col-md-3 {
    -ms-flex: 0 0 25%;
    flex: 0 0 25%;
    max-width: 25%;
  }
  .col-md-4 {
    -ms-flex: 0 0 33.333333%;
    flex: 0 0 33.333333%;
    max-width: 33.333333%;
  }
  .col-md-5 {
    -ms-flex: 0 0 41.666667%;
    flex: 0 0 41.666667%;
    max-width: 41.666667%;
  }
  .col-md-6 {
    -ms-flex: 0 0 50%;
    flex: 0 0 50%;
    max-width: 50%;
  }
  .col-md-7 {
    -ms-flex: 0 0 58.333333%;
    flex: 0 0 58.333333%;
    max-width: 58.333333%;
  }
  .col-md-8 {
    -ms-flex: 0 0 66.666667%;
    flex: 0 0 66.666667%;
    max-width: 66.666667%;
  }
  .col-md-9 {
    -ms-flex: 0 0 75%;
    flex: 0 0 75%;
    max-width: 75%;
  }
  .col-md-10 {
    -ms-flex: 0 0 83.333333%;
    flex: 0 0 83.333333%;
    max-width: 83.333333%;
  }
  .col-md-11 {
    -ms-flex: 0 0 91.666667%;
    flex: 0 0 91.666667%;
    max-width: 91.666667%;
  }
  .col-md-12 {
    -ms-flex: 0 0 100%;
    flex: 0 0 100%;
    max-width: 100%;
  }
  .order-md-first {
    -ms-flex-order: -1;
    order: -1;
  }
  .order-md-last {
    -ms-flex-order: 13;
    order: 13;
  }
  .order-md-0 {
    -ms-flex-order: 0;
    order: 0;
  }
  .order-md-1 {
    -ms-flex-order: 1;
    order: 1;
  }
  .order-md-2 {
    -ms-flex-order: 2;
    order: 2;
  }
  .order-md-3 {
    -ms-flex-order: 3;
    order: 3;
  }
  .order-md-4 {
    -ms-flex-order: 4;
    order: 4;
  }
  .order-md-5 {
    -ms-flex-order: 5;
    order: 5;
  }
  .order-md-6 {
    -ms-flex-order: 6;
    order: 6;
  }
  .order-md-7 {
    -ms-flex-order: 7;
    order: 7;
  }
  .order-md-8 {
    -ms-flex-order: 8;
    order: 8;
  }
  .order-md-9 {
    -ms-flex-order: 9;
    order: 9;
  }
  .order-md-10 {
    -ms-flex-order: 10;
    order: 10;
  }
  .order-md-11 {
    -ms-flex-order: 11;
    order: 11;
  }
  .order-md-12 {
    -ms-flex-order: 12;
    order: 12;
  }
  .offset-md-0 {
    margin-left: 0;
  }
  .offset-md-1 {
    margin-left: 8.333333%;
  }
  .offset-md-2 {
    margin-left: 16.666667%;
  }
  .offset-md-3 {
    margin-left: 25%;
  }
  .offset-md-4 {
    margin-left: 33.333333%;
  }
  .offset-md-5 {
    margin-left: 41.666667%;
  }
  .offset-md-6 {
    margin-left: 50%;
  }
  .offset-md-7 {
    margin-left: 58.333333%;
  }
  .offset-md-8 {
    margin-left: 66.666667%;
  }
  .offset-md-9 {
    margin-left: 75%;
  }
  .offset-md-10 {
    margin-left: 83.333333%;
  }
  .offset-md-11 {
    margin-left: 91.666667%;
  }
}

@media (min-width: 992px) {
  .col-lg {
    -ms-flex-preferred-size: 0;
    flex-basis: 0;
    -ms-flex-positive: 1;
    flex-grow: 1;
    max-width: 100%;
  }
  .row-cols-lg-1 > * {
    -ms-flex: 0 0 100%;
    flex: 0 0 100%;
    max-width: 100%;
  }
  .row-cols-lg-2 > * {
    -ms-flex: 0 0 50%;
    flex: 0 0 50%;
    max-width: 50%;
  }
  .row-cols-lg-3 > * {
    -ms-flex: 0 0 33.333333%;
    flex: 0 0 33.333333%;
    max-width: 33.333333%;
  }
  .row-cols-lg-4 > * {
    -ms-flex: 0 0 25%;
    flex: 0 0 25%;
    max-width: 25%;
  }
  .row-cols-lg-5 > * {
    -ms-flex: 0 0 20%;
    flex: 0 0 20%;
    max-width: 20%;
  }
  .row-cols-lg-6 > * {
    -ms-flex: 0 0 16.666667%;
    flex: 0 0 16.666667%;
    max-width: 16.666667%;
  }
  .col-lg-auto {
    -ms-flex: 0 0 auto;
    flex: 0 0 auto;
    width: auto;
    max-width: 100%;
  }
  .col-lg-1 {
    -ms-flex: 0 0 8.333333%;
    flex: 0 0 8.333333%;
    max-width: 8.333333%;
  }
  .col-lg-2 {
    -ms-flex: 0 0 16.666667%;
    flex: 0 0 16.666667%;
    max-width: 16.666667%;
  }
  .col-lg-3 {
    -ms-flex: 0 0 25%;
    flex: 0 0 25%;
    max-width: 25%;
  }
  .col-lg-4 {
    -ms-flex: 0 0 33.333333%;
    flex: 0 0 33.333333%;
    max-width: 33.333333%;
  }
  .col-lg-5 {
    -ms-flex: 0 0 41.666667%;
    flex: 0 0 41.666667%;
    max-width: 41.666667%;
  }
  .col-lg-6 {
    -ms-flex: 0 0 50%;
    flex: 0 0 50%;
    max-width: 50%;
  }
  .col-lg-7 {
    -ms-flex: 0 0 58.333333%;
    flex: 0 0 58.333333%;
    max-width: 58.333333%;
  }
  .col-lg-8 {
    -ms-flex: 0 0 66.666667%;
    flex: 0 0 66.666667%;
    max-width: 66.666667%;
  }
  .col-lg-9 {
    -ms-flex: 0 0 75%;
    flex: 0 0 75%;
    max-width: 75%;
  }
  .col-lg-10 {
    -ms-flex: 0 0 83.333333%;
    flex: 0 0 83.333333%;
    max-width: 83.333333%;
  }
  .col-lg-11 {
    -ms-flex: 0 0 91.666667%;
    flex: 0 0 91.666667%;
    max-width: 91.666667%;
  }
  .col-lg-12 {
    -ms-flex: 0 0 100%;
    flex: 0 0 100%;
    max-width: 100%;
  }
  .order-lg-first {
    -ms-flex-order: -1;
    order: -1;
  }
  .order-lg-last {
    -ms-flex-order: 13;
    order: 13;
  }
  .order-lg-0 {
    -ms-flex-order: 0;
    order: 0;
  }
  .order-lg-1 {
    -ms-flex-order: 1;
    order: 1;
  }
  .order-lg-2 {
    -ms-flex-order: 2;
    order: 2;
  }
  .order-lg-3 {
    -ms-flex-order: 3;
    order: 3;
  }
  .order-lg-4 {
    -ms-flex-order: 4;
    order: 4;
  }
  .order-lg-5 {
    -ms-flex-order: 5;
    order: 5;
  }
  .order-lg-6 {
    -ms-flex-order: 6;
    order: 6;
  }
  .order-lg-7 {
    -ms-flex-order: 7;
    order: 7;
  }
  .order-lg-8 {
    -ms-flex-order: 8;
    order: 8;
  }
  .order-lg-9 {
    -ms-flex-order: 9;
    order: 9;
  }
  .order-lg-10 {
    -ms-flex-order: 10;
    order: 10;
  }
  .order-lg-11 {
    -ms-flex-order: 11;
    order: 11;
  }
  .order-lg-12 {
    -ms-flex-order: 12;
    order: 12;
  }
  .offset-lg-0 {
    margin-left: 0;
  }
  .offset-lg-1 {
    margin-left: 8.333333%;
  }
  .offset-lg-2 {
    margin-left: 16.666667%;
  }
  .offset-lg-3 {
    margin-left: 25%;
  }
  .offset-lg-4 {
    margin-left: 33.333333%;
  }
  .offset-lg-5 {
    margin-left: 41.666667%;
  }
  .offset-lg-6 {
    margin-left: 50%;
  }
  .offset-lg-7 {
    margin-left: 58.333333%;
  }
  .offset-lg-8 {
    margin-left: 66.666667%;
  }
  .offset-lg-9 {
    margin-left: 75%;
  }
  .offset-lg-10 {
    margin-left: 83.333333%;
  }
  .offset-lg-11 {
    margin-left: 91.666667%;
  }
}

@media (min-width: 1200px) {
  .col-xl {
    -ms-flex-preferred-size: 0;
    flex-basis: 0;
    -ms-flex-positive: 1;
    flex-grow: 1;
    max-width: 100%;
  }
  .row-cols-xl-1 > * {
    -ms-flex: 0 0 100%;
    flex: 0 0 100%;
    max-width: 100%;
  }
  .row-cols-xl-2 > * {
    -ms-flex: 0 0 50%;
    flex: 0 0 50%;
    max-width: 50%;
  }
  .row-cols-xl-3 > * {
    -ms-flex: 0 0 33.333333%;
    flex: 0 0 33.333333%;
    max-width: 33.333333%;
  }
  .row-cols-xl-4 > * {
    -ms-flex: 0 0 25%;
    flex: 0 0 25%;
    max-width: 25%;
  }
  .row-cols-xl-5 > * {
    -ms-flex: 0 0 20%;
    flex: 0 0 20%;
    max-width: 20%;
  }
  .row-cols-xl-6 > * {
    -ms-flex: 0 0 16.666667%;
    flex: 0 0 16.666667%;
    max-width: 16.666667%;
  }
  .col-xl-auto {
    -ms-flex: 0 0 auto;
    flex: 0 0 auto;
    width: auto;
    max-width: 100%;
  }
  .col-xl-1 {
    -ms-flex: 0 0 8.333333%;
    flex: 0 0 8.333333%;
    max-width: 8.333333%;
  }
  .col-xl-2 {
    -ms-flex: 0 0 16.666667%;
    flex: 0 0 16.666667%;
    max-width: 16.666667%;
  }
  .col-xl-3 {
    -ms-flex: 0 0 25%;
    flex: 0 0 25%;
    max-width: 25%;
  }
  .col-xl-4 {
    -ms-flex: 0 0 33.333333%;
    flex: 0 0 33.333333%;
    max-width: 33.333333%;
  }
  .col-xl-5 {
    -ms-flex: 0 0 41.666667%;
    flex: 0 0 41.666667%;
    max-width: 41.666667%;
  }
  .col-xl-6 {
    -ms-flex: 0 0 50%;
    flex: 0 0 50%;
    max-width: 50%;
  }
  .col-xl-7 {
    -ms-flex: 0 0 58.333333%;
    flex: 0 0 58.333333%;
    max-width: 58.333333%;
  }
  .col-xl-8 {
    -ms-flex: 0 0 66.666667%;
    flex: 0 0 66.666667%;
    max-width: 66.666667%;
  }
  .col-xl-9 {
    -ms-flex: 0 0 75%;
    flex: 0 0 75%;
    max-width: 75%;
  }
  .col-xl-10 {
    -ms-flex: 0 0 83.333333%;
    flex: 0 0 83.333333%;
    max-width: 83.333333%;
  }
  .col-xl-11 {
    -ms-flex: 0 0 91.666667%;
    flex: 0 0 91.666667%;
    max-width: 91.666667%;
  }
  .col-xl-12 {
    -ms-flex: 0 0 100%;
    flex: 0 0 100%;
    max-width: 100%;
  }
  .order-xl-first {
    -ms-flex-order: -1;
    order: -1;
  }
  .order-xl-last {
    -ms-flex-order: 13;
    order: 13;
  }
  .order-xl-0 {
    -ms-flex-order: 0;
    order: 0;
  }
  .order-xl-1 {
    -ms-flex-order: 1;
    order: 1;
  }
  .order-xl-2 {
    -ms-flex-order: 2;
    order: 2;
  }
  .order-xl-3 {
    -ms-flex-order: 3;
    order: 3;
  }
  .order-xl-4 {
    -ms-flex-order: 4;
    order: 4;
  }
  .order-xl-5 {
    -ms-flex-order: 5;
    order: 5;
  }
  .order-xl-6 {
    -ms-flex-order: 6;
    order: 6;
  }
  .order-xl-7 {
    -ms-flex-order: 7;
    order: 7;
  }
  .order-xl-8 {
    -ms-flex-order: 8;
    order: 8;
  }
  .order-xl-9 {
    -ms-flex-order: 9;
    order: 9;
  }
  .order-xl-10 {
    -ms-flex-order: 10;
    order: 10;
  }
  .order-xl-11 {
    -ms-flex-order: 11;
    order: 11;
  }
  .order-xl-12 {
    -ms-flex-order: 12;
    order: 12;
  }
  .offset-xl-0 {
    margin-left: 0;
  }
  .offset-xl-1 {
    margin-left: 8.333333%;
  }
  .offset-xl-2 {
    margin-left: 16.666667%;
  }
  .offset-xl-3 {
    margin-left: 25%;
  }
  .offset-xl-4 {
    margin-left: 33.333333%;
  }
  .offset-xl-5 {
    margin-left: 41.666667%;
  }
  .offset-xl-6 {
    margin-left: 50%;
  }
  .offset-xl-7 {
    margin-left: 58.333333%;
  }
  .offset-xl-8 {
    margin-left: 66.666667%;
  }
  .offset-xl-9 {
    margin-left: 75%;
  }
  .offset-xl-10 {
    margin-left: 83.333333%;
  }
  .offset-xl-11 {
    margin-left: 91.666667%;
  }
}

.table {
  width: 100%;
  margin-bottom: 1rem;
  color: #212529;
  background-color: transparent;
}

.table th,
.table td {
  padding: 0.75rem;
  vertical-align: top;
  border-top: 1px solid #dee2e6;
}

.table thead th {
  vertical-align: bottom;
  border-bottom: 2px solid #dee2e6;
}

.table tbody + tbody {
  border-top: 2px solid #dee2e6;
}

.table-sm th,
.table-sm td {
  padding: 0.3rem;
}

.table-bordered {
  border: 1px solid #dee2e6;
}

.table-bordered th,
.table-bordered td {
  border: 1px solid #dee2e6;
}

.table-bordered thead th,
.table-bordered thead td {
  border-bottom-width: 2px;
}

.table-borderless th,
.table-borderless td,
.table-borderless thead th,
.table-borderless tbody + tbody {
  border: 0;
}

.table-striped tbody tr:nth-of-type(odd) {
  background-color: rgba(0, 0, 0, 0.05);
}

.table-hover tbody tr:hover {
  color: #212529;
  background-color: rgba(0, 0, 0, 0.075);
}

.table-primary,
.table-primary > th,
.table-primary > td {
  background-color: #b8daff;
}

.table-primary th,
.table-primary td,
.table-primary thead th,
.table-primary tbody + tbody {
  border-color: #7abaff;
}

.table-hover .table-primary:hover {
  background-color: #9fcdff;
}

.table-hover .table-primary:hover > td,
.table-hover .table-primary:hover > th {
  background-color: #9fcdff;
}

.table-secondary,
.table-secondary > th,
.table-secondary > td {
  background-color: #d6d8db;
}

.table-secondary th,
.table-secondary td,
.table-secondary thead th,
.table-secondary tbody + tbody {
  border-color: #b3b7bb;
}

.table-hover .table-secondary:hover {
  background-color: #c8cbcf;
}

.table-hover .table-secondary:hover > td,
.table-hover .table-secondary:hover > th {
  background-color: #c8cbcf;
}

.table-success,
.table-success > th,
.table-success > td {
  background-color: #c3e6cb;
}

.table-success th,
.table-success td,
.table-success thead th,
.table-success tbody + tbody {
  border-color: #8fd19e;
}

.table-hover .table-success:hover {
  background-color: #b1dfbb;
}

.table-hover .table-success:hover > td,
.table-hover .table-success:hover > th {
  background-color: #b1dfbb;
}

.table-info,
.table-info > th,
.table-info > td {
  background-color: #bee5eb;
}

.table-info th,
.table-info td,
.table-info thead th,
.table-info tbody + tbody {
  border-color: #86cfda;
}

.table-hover .table-info:hover {
  background-color: #abdde5;
}

.table-hover .table-info:hover > td,
.table-hover .table-info:hover > th {
  background-color: #abdde5;
}

.table-warning,
.table-warning > th,
.table-warning > td {
  background-color: #ffeeba;
}

.table-warning th,
.table-warning td,
.table-warning thead th,
.table-warning tbody + tbody {
  border-color: #ffdf7e;
}

.table-hover .table-warning:hover {
  background-color: #ffe8a1;
}

.table-hover .table-warning:hover > td,
.table-hover .table-warning:hover > th {
  background-color: #ffe8a1;
}

.table-danger,
.table-danger > th,
.table-danger > td {
  background-color: #f5c6cb;
}

.table-danger th,
.table-danger td,
.table-danger thead th,
.table-danger tbody + tbody {
  border-color: #ed969e;
}

.table-hover .table-danger:hover {
  background-color: #f1b0b7;
}

.table-hover .table-danger:hover > td,
.table-hover .table-danger:hover > th {
  background-color: #f1b0b7;
}

.table-light,
.table-light > th,
.table-light > td {
  background-color: #fdfdfe;
}

.table-light th,
.table-light td,
.table-light thead th,
.table-light tbody + tbody {
  border-color: #fbfcfc;
}

.table-hover .table-light:hover {
  background-color: #ececf6;
}

.table-hover .table-light:hover > td,
.table-hover .table-light:hover > th {
  background-color: #ececf6;
}

.table-dark,
.table-dark > th,
.table-dark > td {
  background-color: #c6c8ca;
}

.table-dark th,
.table-dark td,
.table-dark thead th,
.table-dark tbody + tbody {
  border-color: #95999c;
}

.table-hover .table-dark:hover {
  background-color: #b9bbbe;
}

.table-hover .table-dark:hover > td,
.table-hover .table-dark:hover > th {
  background-color: #b9bbbe;
}

.table-active,
.table-active > th,
.table-active > td {
  background-color: rgba(0, 0, 0, 0.075);
}

.table-hover .table-active:hover {
  background-color: rgba(0, 0, 0, 0.075);
}

.table-hover .table-active:hover > td,
.table-hover .table-active:hover > th {
  background-color: rgba(0, 0, 0, 0.075);
}

.table .thead-dark th {
  color: #fff;
  background-color: #212529;
  border-color: #383f45;
}

.table .thead-light th {
  color: #495057;
  background-color: #e9ecef;
  border-color: #dee2e6;
}

.table-dark {
  color: #fff;
  background-color: #212529;
}

.table-dark th,
.table-dark td,
.table-dark thead th {
  border-color: #383f45;
}

.table-dark.table-bordered {
  border: 0;
}

.table-dark.table-striped tbody tr:nth-of-type(odd) {
  background-color: rgba(255, 255, 255, 0.05);
}

.table-dark.table-hover tbody tr:hover {
  color: #fff;
  background-color: rgba(255, 255, 255, 0.075);
}

@media (max-width: 575.98px) {
  .table-responsive-sm {
    display: block;
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
  }
  .table-responsive-sm > .table-bordered {
    border: 0;
  }
}

@media (max-width: 767.98px) {
  .table-responsive-md {
    display: block;
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
  }
  .table-responsive-md > .table-bordered {
    border: 0;
  }
}

@media (max-width: 991.98px) {
  .table-responsive-lg {
    display: block;
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
  }
  .table-responsive-lg > .table-bordered {
    border: 0;
  }
}

@media (max-width: 1199.98px) {
  .table-responsive-xl {
    display: block;
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
  }
  .table-responsive-xl > .table-bordered {
    border: 0;
  }
}

.table-responsive {
  display: block;
  width: 100%;
  overflow-x: auto;
  -webkit-overflow-scrolling: touch;
}

.table-responsive > .table-bordered {
  border: 0;
}

.form-control {
  display: block;
  width: 100%;
  height: calc(2.25rem + 2px);
  padding: 0.375rem 0.75rem;
  font-size: 1rem;
  font-weight: 400;
  line-height: 1.5;
  color: #495057;
  background-color: #fff;
  background-clip: padding-box;
  border: 1px solid #ced4da;
  border-radius: 0.25rem;
  box-shadow: inset 0 0 0 rgba(0, 0, 0, 0);
  transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

@media (prefers-reduced-motion: reduce) {
  .form-control {
    transition: none;
  }
}
 
.valid-tooltip {
  position: absolute;
  top: 100%;
  left: 0;
  z-index: 5;
  display: none;
  max-width: 100%;
  padding: 0.25rem 0.5rem;
  margin-top: .1rem;
  font-size: 0.875rem;
  line-height: 1.5;
  color: #fff;
  background-color: rgba(40, 167, 69, 0.9);
  border-radius: 0.25rem;
}

.was-validated :valid ~ .valid-feedback,
.was-validated :valid ~ .valid-tooltip,
.is-valid ~ .valid-feedback,
.is-valid ~ .valid-tooltip {
  display: block;
}

.was-validated .form-control:valid, .form-control.is-valid {
  border-color: #28a745;
  padding-right: 2.25rem;
  background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8' viewBox='0 0 8 8'%3e%3cpath fill='%2328a745' d='M2.3 6.73L.6 4.53c-.4-1.04.46-1.4 1.1-.8l1.1 1.4 3.4-3.8c.6-.63 1.6-.27 1.2.7l-4 4.6c-.43.5-.8.4-1.1.1z'/%3e%3c/svg%3e");
  background-repeat: no-repeat;
  background-position: right calc(0.375em + 0.1875rem) center;
  background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
}

.was-validated .form-control:valid:focus, .form-control.is-valid:focus {
  border-color: #28a745;
  box-shadow: 0 0 0 0 rgba(40, 167, 69, 0.25);
}

.was-validated textarea.form-control:valid, textarea.form-control.is-valid {
  padding-right: 2.25rem;
  background-position: top calc(0.375em + 0.1875rem) right calc(0.375em + 0.1875rem);
}

.was-validated .custom-select:valid, .custom-select.is-valid {
  border-color: #28a745;
  padding-right: calc(0.75em + 2.3125rem);
  background: url("data:image/svg+xml;charset=utf8,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 4 5'%3E%3Cpath fill='%23343a40' d='M2 0L0 2h4zm0 5L0 3h4z'/%3E%3C/svg%3E") no-repeat right 0.75rem center/8px 10px, url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8' viewBox='0 0 8 8'%3e%3cpath fill='%2328a745' d='M2.3 6.73L.6 4.53c-.4-1.04.46-1.4 1.1-.8l1.1 1.4 3.4-3.8c.6-.63 1.6-.27 1.2.7l-4 4.6c-.43.5-.8.4-1.1.1z'/%3e%3c/svg%3e") #fff no-repeat center right 1.75rem/calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
}

.was-validated .custom-select:valid:focus, .custom-select.is-valid:focus {
  border-color: #28a745;
  box-shadow: 0 0 0 0 rgba(40, 167, 69, 0.25);
}

.was-validated .form-check-input:valid ~ .form-check-label, .form-check-input.is-valid ~ .form-check-label {
  color: #28a745;
}

.was-validated .form-check-input:valid ~ .valid-feedback,
.was-validated .form-check-input:valid ~ .valid-tooltip, .form-check-input.is-valid ~ .valid-feedback,
.form-check-input.is-valid ~ .valid-tooltip {
  display: block;
}

.was-validated .custom-control-input:valid ~ .custom-control-label, .custom-control-input.is-valid ~ .custom-control-label {
  color: #28a745;
}

.was-validated .custom-control-input:valid ~ .custom-control-label::before, .custom-control-input.is-valid ~ .custom-control-label::before {
  border-color: #28a745;
}

.was-validated .custom-control-input:valid:checked ~ .custom-control-label::before, .custom-control-input.is-valid:checked ~ .custom-control-label::before {
  border-color: #34ce57;
  background-color: #34ce57;
}

.was-validated .custom-control-input:valid:focus ~ .custom-control-label::before, .custom-control-input.is-valid:focus ~ .custom-control-label::before {
  box-shadow: 0 0 0 0 rgba(40, 167, 69, 0.25);
}

.was-validated .custom-control-input:valid:focus:not(:checked) ~ .custom-control-label::before, .custom-control-input.is-valid:focus:not(:checked) ~ .custom-control-label::before {
  border-color: #28a745;
}

.was-validated .custom-file-input:valid ~ .custom-file-label, .custom-file-input.is-valid ~ .custom-file-label {
  border-color: #28a745;
}

.was-validated .custom-file-input:valid:focus ~ .custom-file-label, .custom-file-input.is-valid:focus ~ .custom-file-label {
  border-color: #28a745;
  box-shadow: 0 0 0 0 rgba(40, 167, 69, 0.25);
}

.invalid-feedback {
  display: none;
  width: 100%;
  margin-top: 0.25rem;
  font-size: 80%;
  color: #dc3545;
}

.invalid-tooltip {
  position: absolute;
  top: 100%;
  left: 0;
  z-index: 5;
  display: none;
  max-width: 100%;
  padding: 0.25rem 0.5rem;
  margin-top: .1rem;
  font-size: 0.875rem;
  line-height: 1.5;
  color: #fff;
  background-color: rgba(220, 53, 69, 0.9);
  border-radius: 0.25rem;
}

.was-validated :invalid ~ .invalid-feedback,
.was-validated :invalid ~ .invalid-tooltip,
.is-invalid ~ .invalid-feedback,
.is-invalid ~ .invalid-tooltip {
  display: block;
}

.was-validated .form-control:invalid, .form-control.is-invalid {
  border-color: #dc3545;
  padding-right: 2.25rem;
  background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='none' stroke='%23dc3545' viewBox='0 0 12 12'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
  background-repeat: no-repeat;
  background-position: right calc(0.375em + 0.1875rem) center;
  background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
}

.was-validated .form-control:invalid:focus, .form-control.is-invalid:focus {
  border-color: #dc3545;
  box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.25);
}

.was-validated textarea.form-control:invalid, textarea.form-control.is-invalid {
  padding-right: 2.25rem;
  background-position: top calc(0.375em + 0.1875rem) right calc(0.375em + 0.1875rem);
}

.was-validated .custom-select:invalid, .custom-select.is-invalid {
  border-color: #dc3545;
  padding-right: calc(0.75em + 2.3125rem);
  background: url("data:image/svg+xml;charset=utf8,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 4 5'%3E%3Cpath fill='%23343a40' d='M2 0L0 2h4zm0 5L0 3h4z'/%3E%3C/svg%3E") no-repeat right 0.75rem center/8px 10px, url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='none' stroke='%23dc3545' viewBox='0 0 12 12'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e") #fff no-repeat center right 1.75rem/calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
}

.was-validated .custom-select:invalid:focus, .custom-select.is-invalid:focus {
  border-color: #dc3545;
  box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.25);
}

.was-validated .form-check-input:invalid ~ .form-check-label, .form-check-input.is-invalid ~ .form-check-label {
  color: #dc3545;
}

.was-validated .form-check-input:invalid ~ .invalid-feedback,
.was-validated .form-check-input:invalid ~ .invalid-tooltip, .form-check-input.is-invalid ~ .invalid-feedback,
.form-check-input.is-invalid ~ .invalid-tooltip {
  display: block;
}

.was-validated .custom-control-input:invalid ~ .custom-control-label, .custom-control-input.is-invalid ~ .custom-control-label {
  color: #dc3545;
}

.was-validated .custom-control-input:invalid ~ .custom-control-label::before, .custom-control-input.is-invalid ~ .custom-control-label::before {
  border-color: #dc3545;
}

.was-validated .custom-control-input:invalid:checked ~ .custom-control-label::before, .custom-control-input.is-invalid:checked ~ .custom-control-label::before {
  border-color: #e4606d;
  background-color: #e4606d;
}

.was-validated .custom-control-input:invalid:focus ~ .custom-control-label::before, .custom-control-input.is-invalid:focus ~ .custom-control-label::before {
  box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.25);
}

.was-validated .custom-control-input:invalid:focus:not(:checked) ~ .custom-control-label::before, .custom-control-input.is-invalid:focus:not(:checked) ~ .custom-control-label::before {
  border-color: #dc3545;
}

.was-validated .custom-file-input:invalid ~ .custom-file-label, .custom-file-input.is-invalid ~ .custom-file-label {
  border-color: #dc3545;
}

.was-validated .custom-file-input:invalid:focus ~ .custom-file-label, .custom-file-input.is-invalid:focus ~ .custom-file-label {
  border-color: #dc3545;
  box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.25);
}

.form-inline {
  display: -ms-flexbox;
  display: flex;
  -ms-flex-flow: row wrap;
  flex-flow: row wrap;
  -ms-flex-align: center;
  align-items: center;
}

.form-inline .form-check {
  width: 100%;
}

@media (min-width: 576px) {
  .form-inline label {
    display: -ms-flexbox;
    display: flex;
    -ms-flex-align: center;
    align-items: center;
    -ms-flex-pack: center;
    justify-content: center;
    margin-bottom: 0;
  }
  .form-inline .form-group {
    display: -ms-flexbox;
    display: flex;
    -ms-flex: 0 0 auto;
    flex: 0 0 auto;
    -ms-flex-flow: row wrap;
    flex-flow: row wrap;
    -ms-flex-align: center;
    align-items: center;
    margin-bottom: 0;
  }
  .form-inline .form-control {
    display: inline-block;
    width: auto;
    vertical-align: middle;
  }
  .form-inline .form-control-plaintext {
    display: inline-block;
  }
  .form-inline .input-group,
  .form-inline .custom-select {
    width: auto;
  }
  .form-inline .form-check {
    display: -ms-flexbox;
    display: flex;
    -ms-flex-align: center;
    align-items: center;
    -ms-flex-pack: center;
    justify-content: center;
    width: auto;
    padding-left: 0;
  }
  .form-inline .form-check-input {
    position: relative;
    -ms-flex-negative: 0;
    flex-shrink: 0;
    margin-top: 0;
    margin-right: 0.25rem;
    margin-left: 0;
  }
  .form-inline .custom-control {
    -ms-flex-align: center;
    align-items: center;
    -ms-flex-pack: center;
    justify-content: center;
  }
  .form-inline .custom-control-label {
    margin-bottom: 0;
  }
}

.btn {
  display: inline-block;
  font-weight: 400;
  color: #212529;
  text-align: center;
  vertical-align: middle;
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
  background-color: transparent;
  border: 1px solid transparent;
  padding: 0.375rem 0.75rem;
  font-size: 1rem;
  line-height: 1.5;
  border-radius: 0.25rem;
  transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

@media (prefers-reduced-motion: reduce) {
  .btn {
    transition: none;
  }
}

.btn:hover {
  color: #212529;
  text-decoration: none;
}

.btn:focus, .btn.focus {
  outline: 0;
  box-shadow: none;
}

.btn.disabled, .btn:disabled {
  opacity: 0.65;
  box-shadow: none;
}

.btn:not(:disabled):not(.disabled) {
  cursor: pointer;
}

.btn:not(:disabled):not(.disabled):active, .btn:not(:disabled):not(.disabled).active {
  box-shadow: none;
}

a.btn.disabled,
fieldset:disabled a.btn {
  pointer-events: none;
}

.btn-primary {
  color: #fff;
  background-color: #007bff;
  border-color: #007bff;
  box-shadow: none;
}

.btn-primary:hover {
  color: #fff;
  background-color: #0069d9;
  border-color: #0062cc;
}

.btn-primary:focus, .btn-primary.focus {
  color: #fff;
  background-color: #0069d9;
  border-color: #0062cc;
  box-shadow: 0 0 0 0 rgba(38, 143, 255, 0.5);
}

.btn-primary.disabled, .btn-primary:disabled {
  color: #fff;
  background-color: #007bff;
  border-color: #007bff;
}

.btn-primary:not(:disabled):not(.disabled):active, .btn-primary:not(:disabled):not(.disabled).active,
.show > .btn-primary.dropdown-toggle {
  color: #fff;
  background-color: #0062cc;
  border-color: #005cbf;
}

.btn-primary:not(:disabled):not(.disabled):active:focus, .btn-primary:not(:disabled):not(.disabled).active:focus,
.show > .btn-primary.dropdown-toggle:focus {
  box-shadow: 0 0 0 0 rgba(38, 143, 255, 0.5);
}

.btn-secondary {
  color: #fff;
  background-color: #6c757d;
  border-color: #6c757d;
  box-shadow: none;
}

.btn-secondary:hover {
  color: #fff;
  background-color: #5a6268;
  border-color: #545b62;
}

.btn-secondary:focus, .btn-secondary.focus {
  color: #fff;
  background-color: #5a6268;
  border-color: #545b62;
  box-shadow: 0 0 0 0 rgba(130, 138, 145, 0.5);
}

.btn-secondary.disabled, .btn-secondary:disabled {
  color: #fff;
  background-color: #6c757d;
  border-color: #6c757d;
}

.btn-secondary:not(:disabled):not(.disabled):active, .btn-secondary:not(:disabled):not(.disabled).active,
.show > .btn-secondary.dropdown-toggle {
  color: #fff;
  background-color: #545b62;
  border-color: #4e555b;
}

.btn-secondary:not(:disabled):not(.disabled):active:focus, .btn-secondary:not(:disabled):not(.disabled).active:focus,
.show > .btn-secondary.dropdown-toggle:focus {
  box-shadow: 0 0 0 0 rgba(130, 138, 145, 0.5);
}

.btn-success {
  color: #fff;
  background-color: #28a745;
  border-color: #28a745;
  box-shadow: none;
}

.btn-success:hover {
  color: #fff;
  background-color: #218838;
  border-color: #1e7e34;
}

.btn-success:focus, .btn-success.focus {
  color: #fff;
  background-color: #218838;
  border-color: #1e7e34;
  box-shadow: 0 0 0 0 rgba(72, 180, 97, 0.5);
}

.btn-success.disabled, .btn-success:disabled {
  color: #fff;
  background-color: #28a745;
  border-color: #28a745;
}

.btn-success:not(:disabled):not(.disabled):active, .btn-success:not(:disabled):not(.disabled).active,
.show > .btn-success.dropdown-toggle {
  color: #fff;
  background-color: #1e7e34;
  border-color: #1c7430;
}

.btn-success:not(:disabled):not(.disabled):active:focus, .btn-success:not(:disabled):not(.disabled).active:focus,
.show > .btn-success.dropdown-toggle:focus {
  box-shadow: 0 0 0 0 rgba(72, 180, 97, 0.5);
}

.btn-info {
  color: #fff;
  background-color: #17a2b8;
  border-color: #17a2b8;
  box-shadow: none;
}

.btn-info:hover {
  color: #fff;
  background-color: #138496;
  border-color: #117a8b;
}

.btn-info:focus, .btn-info.focus {
  color: #fff;
  background-color: #138496;
  border-color: #117a8b;
  box-shadow: 0 0 0 0 rgba(58, 176, 195, 0.5);
}

.btn-info.disabled, .btn-info:disabled {
  color: #fff;
  background-color: #17a2b8;
  border-color: #17a2b8;
}

.btn-info:not(:disabled):not(.disabled):active, .btn-info:not(:disabled):not(.disabled).active,
.show > .btn-info.dropdown-toggle {
  color: #fff;
  background-color: #117a8b;
  border-color: #10707f;
}

.btn-info:not(:disabled):not(.disabled):active:focus, .btn-info:not(:disabled):not(.disabled).active:focus,
.show > .btn-info.dropdown-toggle:focus {
  box-shadow: 0 0 0 0 rgba(58, 176, 195, 0.5);
}

.btn-warning {
  color: #1f2d3d;
  background-color: #ffc107;
  border-color: #ffc107;
  box-shadow: none;
}

.btn-warning:hover {
  color: #1f2d3d;
  background-color: #e0a800;
  border-color: #d39e00;
}

.btn-warning:focus, .btn-warning.focus {
  color: #1f2d3d;
  background-color: #e0a800;
  border-color: #d39e00;
  box-shadow: 0 0 0 0 rgba(221, 171, 15, 0.5);
}

.btn-warning.disabled, .btn-warning:disabled {
  color: #1f2d3d;
  background-color: #ffc107;
  border-color: #ffc107;
}

.btn-warning:not(:disabled):not(.disabled):active, .btn-warning:not(:disabled):not(.disabled).active,
.show > .btn-warning.dropdown-toggle {
  color: #1f2d3d;
  background-color: #d39e00;
  border-color: #c69500;
}

.btn-warning:not(:disabled):not(.disabled):active:focus, .btn-warning:not(:disabled):not(.disabled).active:focus,
.show > .btn-warning.dropdown-toggle:focus {
  box-shadow: 0 0 0 0 rgba(221, 171, 15, 0.5);
}

.btn-danger {
  color: #fff;
  background-color: #dc3545;
  border-color: #dc3545;
  box-shadow: none;
}

.btn-danger:hover {
  color: #fff;
  background-color: #c82333;
  border-color: #bd2130;
}

.btn-danger:focus, .btn-danger.focus {
  color: #fff;
  background-color: #c82333;
  border-color: #bd2130;
  box-shadow: 0 0 0 0 rgba(225, 83, 97, 0.5);
}

.btn-danger.disabled, .btn-danger:disabled {
  color: #fff;
  background-color: #dc3545;
  border-color: #dc3545;
}

.btn-danger:not(:disabled):not(.disabled):active, .btn-danger:not(:disabled):not(.disabled).active,
.show > .btn-danger.dropdown-toggle {
  color: #fff;
  background-color: #bd2130;
  border-color: #b21f2d;
}

.btn-danger:not(:disabled):not(.disabled):active:focus, .btn-danger:not(:disabled):not(.disabled).active:focus,
.show > .btn-danger.dropdown-toggle:focus {
  box-shadow: 0 0 0 0 rgba(225, 83, 97, 0.5);
}

.btn-light {
  color: #1f2d3d;
  background-color: #f8f9fa;
  border-color: #f8f9fa;
  box-shadow: none;
}

.btn-light:hover {
  color: #1f2d3d;
  background-color: #e2e6ea;
  border-color: #dae0e5;
}

.btn-light:focus, .btn-light.focus {
  color: #1f2d3d;
  background-color: #e2e6ea;
  border-color: #dae0e5;
  box-shadow: 0 0 0 0 rgba(215, 218, 222, 0.5);
}

.btn-light.disabled, .btn-light:disabled {
  color: #1f2d3d;
  background-color: #f8f9fa;
  border-color: #f8f9fa;
}

.btn-light:not(:disabled):not(.disabled):active, .btn-light:not(:disabled):not(.disabled).active,
.show > .btn-light.dropdown-toggle {
  color: #1f2d3d;
  background-color: #dae0e5;
  border-color: #d3d9df;
}

.btn-light:not(:disabled):not(.disabled):active:focus, .btn-light:not(:disabled):not(.disabled).active:focus,
.show > .btn-light.dropdown-toggle:focus {
  box-shadow: 0 0 0 0 rgba(215, 218, 222, 0.5);
}

.btn-dark {
  color: #fff;
  background-color: #343a40;
  border-color: #343a40;
  box-shadow: none;
}

.btn-dark:hover {
  color: #fff;
  background-color: #23272b;
  border-color: #1d2124;
}

.btn-dark:focus, .btn-dark.focus {
  color: #fff;
  background-color: #23272b;
  border-color: #1d2124;
  box-shadow: 0 0 0 0 rgba(82, 88, 93, 0.5);
}

.btn-dark.disabled, .btn-dark:disabled {
  color: #fff;
  background-color: #343a40;
  border-color: #343a40;
}

.btn-dark:not(:disabled):not(.disabled):active, .btn-dark:not(:disabled):not(.disabled).active,
.show > .btn-dark.dropdown-toggle {
  color: #fff;
  background-color: #1d2124;
  border-color: #171a1d;
}

.btn-dark:not(:disabled):not(.disabled):active:focus, .btn-dark:not(:disabled):not(.disabled).active:focus,
.show > .btn-dark.dropdown-toggle:focus {
  box-shadow: 0 0 0 0 rgba(82, 88, 93, 0.5);
}

.btn-outline-primary {
  color: #007bff;
  border-color: #007bff;
}

.btn-outline-primary:hover {
  color: #fff;
  background-color: #007bff;
  border-color: #007bff;
}

.btn-outline-primary:focus, .btn-outline-primary.focus {
  box-shadow: 0 0 0 0 rgba(0, 123, 255, 0.5);
}

.btn-outline-primary.disabled, .btn-outline-primary:disabled {
  color: #007bff;
  background-color: transparent;
}

.btn-outline-primary:not(:disabled):not(.disabled):active, .btn-outline-primary:not(:disabled):not(.disabled).active,
.show > .btn-outline-primary.dropdown-toggle {
  color: #fff;
  background-color: #007bff;
  border-color: #007bff;
}

.btn-outline-primary:not(:disabled):not(.disabled):active:focus, .btn-outline-primary:not(:disabled):not(.disabled).active:focus,
.show > .btn-outline-primary.dropdown-toggle:focus {
  box-shadow: 0 0 0 0 rgba(0, 123, 255, 0.5);
}

.btn-outline-secondary {
  color: #6c757d;
  border-color: #6c757d;
}

.btn-outline-secondary:hover {
  color: #fff;
  background-color: #6c757d;
  border-color: #6c757d;
}

.btn-outline-secondary:focus, .btn-outline-secondary.focus {
  box-shadow: 0 0 0 0 rgba(108, 117, 125, 0.5);
}

.btn-outline-secondary.disabled, .btn-outline-secondary:disabled {
  color: #6c757d;
  background-color: transparent;
}

.btn-outline-secondary:not(:disabled):not(.disabled):active, .btn-outline-secondary:not(:disabled):not(.disabled).active,
.show > .btn-outline-secondary.dropdown-toggle {
  color: #fff;
  background-color: #6c757d;
  border-color: #6c757d;
}

.btn-outline-secondary:not(:disabled):not(.disabled):active:focus, .btn-outline-secondary:not(:disabled):not(.disabled).active:focus,
.show > .btn-outline-secondary.dropdown-toggle:focus {
  box-shadow: 0 0 0 0 rgba(108, 117, 125, 0.5);
}

.btn-outline-success {
  color: #28a745;
  border-color: #28a745;
}

.btn-outline-success:hover {
  color: #fff;
  background-color: #28a745;
  border-color: #28a745;
}

.btn-outline-success:focus, .btn-outline-success.focus {
  box-shadow: 0 0 0 0 rgba(40, 167, 69, 0.5);
}

.btn-outline-success.disabled, .btn-outline-success:disabled {
  color: #28a745;
  background-color: transparent;
}

.btn-outline-success:not(:disabled):not(.disabled):active, .btn-outline-success:not(:disabled):not(.disabled).active,
.show > .btn-outline-success.dropdown-toggle {
  color: #fff;
  background-color: #28a745;
  border-color: #28a745;
}

.btn-outline-success:not(:disabled):not(.disabled):active:focus, .btn-outline-success:not(:disabled):not(.disabled).active:focus,
.show > .btn-outline-success.dropdown-toggle:focus {
  box-shadow: 0 0 0 0 rgba(40, 167, 69, 0.5);
}

.btn-outline-info {
  color: #17a2b8;
  border-color: #17a2b8;
}

.btn-outline-info:hover {
  color: #fff;
  background-color: #17a2b8;
  border-color: #17a2b8;
}

.btn-outline-info:focus, .btn-outline-info.focus {
  box-shadow: 0 0 0 0 rgba(23, 162, 184, 0.5);
}

.btn-outline-info.disabled, .btn-outline-info:disabled {
  color: #17a2b8;
  background-color: transparent;
}

.btn-outline-info:not(:disabled):not(.disabled):active, .btn-outline-info:not(:disabled):not(.disabled).active,
.show > .btn-outline-info.dropdown-toggle {
  color: #fff;
  background-color: #17a2b8;
  border-color: #17a2b8;
}

.btn-outline-info:not(:disabled):not(.disabled):active:focus, .btn-outline-info:not(:disabled):not(.disabled).active:focus,
.show > .btn-outline-info.dropdown-toggle:focus {
  box-shadow: 0 0 0 0 rgba(23, 162, 184, 0.5);
}

.btn-outline-warning {
  color: #ffc107;
  border-color: #ffc107;
}

.btn-outline-warning:hover {
  color: #1f2d3d;
  background-color: #ffc107;
  border-color: #ffc107;
}

.btn-outline-warning:focus, .btn-outline-warning.focus {
  box-shadow: 0 0 0 0 rgba(255, 193, 7, 0.5);
}

.btn-outline-warning.disabled, .btn-outline-warning:disabled {
  color: #ffc107;
  background-color: transparent;
}

.btn-outline-warning:not(:disabled):not(.disabled):active, .btn-outline-warning:not(:disabled):not(.disabled).active,
.show > .btn-outline-warning.dropdown-toggle {
  color: #1f2d3d;
  background-color: #ffc107;
  border-color: #ffc107;
}

.btn-outline-warning:not(:disabled):not(.disabled):active:focus, .btn-outline-warning:not(:disabled):not(.disabled).active:focus,
.show > .btn-outline-warning.dropdown-toggle:focus {
  box-shadow: 0 0 0 0 rgba(255, 193, 7, 0.5);
}

.btn-outline-danger {
  color: #dc3545;
  border-color: #dc3545;
}

.btn-outline-danger:hover {
  color: #fff;
  background-color: #dc3545;
  border-color: #dc3545;
}

.btn-outline-danger:focus, .btn-outline-danger.focus {
  box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.5);
}

.btn-outline-danger.disabled, .btn-outline-danger:disabled {
  color: #dc3545;
  background-color: transparent;
}

.btn-outline-danger:not(:disabled):not(.disabled):active, .btn-outline-danger:not(:disabled):not(.disabled).active,
.show > .btn-outline-danger.dropdown-toggle {
  color: #fff;
  background-color: #dc3545;
  border-color: #dc3545;
}

.btn-outline-danger:not(:disabled):not(.disabled):active:focus, .btn-outline-danger:not(:disabled):not(.disabled).active:focus,
.show > .btn-outline-danger.dropdown-toggle:focus {
  box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.5);
}

.btn-outline-light {
  color: #f8f9fa;
  border-color: #f8f9fa;
}

.btn-outline-light:hover {
  color: #1f2d3d;
  background-color: #f8f9fa;
  border-color: #f8f9fa;
}

.btn-outline-light:focus, .btn-outline-light.focus {
  box-shadow: 0 0 0 0 rgba(248, 249, 250, 0.5);
}

.btn-outline-light.disabled, .btn-outline-light:disabled {
  color: #f8f9fa;
  background-color: transparent;
}

.btn-outline-light:not(:disabled):not(.disabled):active, .btn-outline-light:not(:disabled):not(.disabled).active,
.show > .btn-outline-light.dropdown-toggle {
  color: #1f2d3d;
  background-color: #f8f9fa;
  border-color: #f8f9fa;
}

.btn-outline-light:not(:disabled):not(.disabled):active:focus, .btn-outline-light:not(:disabled):not(.disabled).active:focus,
.show > .btn-outline-light.dropdown-toggle:focus {
  box-shadow: 0 0 0 0 rgba(248, 249, 250, 0.5);
}

.btn-outline-dark {
  color: #343a40;
  border-color: #343a40;
}

.btn-outline-dark:hover {
  color: #fff;
  background-color: #343a40;
  border-color: #343a40;
}

.btn-outline-dark:focus, .btn-outline-dark.focus {
  box-shadow: 0 0 0 0 rgba(52, 58, 64, 0.5);
}

.btn-outline-dark.disabled, .btn-outline-dark:disabled {
  color: #343a40;
  background-color: transparent;
}

.btn-outline-dark:not(:disabled):not(.disabled):active, .btn-outline-dark:not(:disabled):not(.disabled).active,
.show > .btn-outline-dark.dropdown-toggle {
  color: #fff;
  background-color: #343a40;
  border-color: #343a40;
}

.btn-outline-dark:not(:disabled):not(.disabled):active:focus, .btn-outline-dark:not(:disabled):not(.disabled).active:focus,
.show > .btn-outline-dark.dropdown-toggle:focus {
  box-shadow: 0 0 0 0 rgba(52, 58, 64, 0.5);
}

.btn-link {
  font-weight: 400;
  color: #007bff;
  text-decoration: none;
}

.btn-link:hover {
  color: #0056b3;
  text-decoration: none;
}

.btn-link:focus, .btn-link.focus {
  text-decoration: none;
}

.btn-link:disabled, .btn-link.disabled {
  color: #6c757d;
  pointer-events: none;
}

.btn-lg, .btn-group-lg > .btn {
  padding: 0.5rem 1rem;
  font-size: 1.25rem;
  line-height: 1.5;
  border-radius: 0.3rem;
}

.btn-sm, .btn-group-sm > .btn {
  padding: 0.25rem 0.5rem;
  font-size: 0.875rem;
  line-height: 1.5;
  border-radius: 0.2rem;
}

.btn-block {
  display: block;
  width: 100%;
}

.btn-block + .btn-block {
  margin-top: 0.5rem;
}

input[type="submit"].btn-block,
input[type="reset"].btn-block,
input[type="button"].btn-block {
  width: 100%;
}

.fade {
  transition: opacity 0.15s linear;
}

@media (prefers-reduced-motion: reduce) {
  .fade {
    transition: none;
  }
}

.fade:not(.show) {
  opacity: 0;
}

.collapse:not(.show) {
  display: none;
}

.collapsing {
  position: relative;
  height: 0;
  overflow: hidden;
  transition: height 0.35s ease;
}

@media (prefers-reduced-motion: reduce) {
  .collapsing {
    transition: none;
  }
}

.dropup,
.dropright,
.dropdown,
.dropleft {
  position: relative;
}

.dropdown-toggle {
  white-space: nowrap;
}

.dropdown-toggle::after {
  display: inline-block;
  margin-left: 0.255em;
  vertical-align: 0.255em;
  content: "";
  border-top: 0.3em solid;
  border-right: 0.3em solid transparent;
  border-bottom: 0;
  border-left: 0.3em solid transparent;
}

.dropdown-toggle:empty::after {
  margin-left: 0;
}

.dropdown-menu {
  position: absolute;
  top: 100%;
  left: 0;
  z-index: 1000;
  display: none;
  float: left;
  min-width: 10rem;
  padding: 0.5rem 0;
  margin: 0.125rem 0 0;
  font-size: 1rem;
  color: #212529;
  text-align: left;
  list-style: none;
  background-color: #fff;
  background-clip: padding-box;
  border: 1px solid rgba(0, 0, 0, 0.15);
  border-radius: 0.25rem;
  box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.175);
}

.dropdown-menu-left {
  right: auto;
  left: 0;
}

.dropdown-menu-right {
  right: 0;
  left: auto;
}

@media (min-width: 576px) {
  .dropdown-menu-sm-left {
    right: auto;
    left: 0;
  }
  .dropdown-menu-sm-right {
    right: 0;
    left: auto;
  }
}

@media (min-width: 768px) {
  .dropdown-menu-md-left {
    right: auto;
    left: 0;
  }
  .dropdown-menu-md-right {
    right: 0;
    left: auto;
  }
}

@media (min-width: 992px) {
  .dropdown-menu-lg-left {
    right: auto;
    left: 0;
  }
  .dropdown-menu-lg-right {
    right: 0;
    left: auto;
  }
}

@media (min-width: 1200px) {
  .dropdown-menu-xl-left {
    right: auto;
    left: 0;
  }
  .dropdown-menu-xl-right {
    right: 0;
    left: auto;
  }
}

.dropup .dropdown-menu {
  top: auto;
  bottom: 100%;
  margin-top: 0;
  margin-bottom: 0.125rem;
}

.dropup .dropdown-toggle::after {
  display: inline-block;
  margin-left: 0.255em;
  vertical-align: 0.255em;
  content: "";
  border-top: 0;
  border-right: 0.3em solid transparent;
  border-bottom: 0.3em solid;
  border-left: 0.3em solid transparent;
}

.dropup .dropdown-toggle:empty::after {
  margin-left: 0;
}

.dropright .dropdown-menu {
  top: 0;
  right: auto;
  left: 100%;
  margin-top: 0;
  margin-left: 0.125rem;
}

.dropright .dropdown-toggle::after {
  display: inline-block;
  margin-left: 0.255em;
  vertical-align: 0.255em;
  content: "";
  border-top: 0.3em solid transparent;
  border-right: 0;
  border-bottom: 0.3em solid transparent;
  border-left: 0.3em solid;
}

.dropright .dropdown-toggle:empty::after {
  margin-left: 0;
}

.dropright .dropdown-toggle::after {
  vertical-align: 0;
}

.dropleft .dropdown-menu {
  top: 0;
  right: 100%;
  left: auto;
  margin-top: 0;
  margin-right: 0.125rem;
}

.dropleft .dropdown-toggle::after {
  display: inline-block;
  margin-left: 0.255em;
  vertical-align: 0.255em;
  content: "";
}

.dropleft .dropdown-toggle::after {
  display: none;
}

.dropleft .dropdown-toggle::before {
  display: inline-block;
  margin-right: 0.255em;
  vertical-align: 0.255em;
  content: "";
  border-top: 0.3em solid transparent;
  border-right: 0.3em solid;
  border-bottom: 0.3em solid transparent;
}

.dropleft .dropdown-toggle:empty::after {
  margin-left: 0;
}

.dropleft .dropdown-toggle::before {
  vertical-align: 0;
}

.dropdown-menu[x-placement^="top"], .dropdown-menu[x-placement^="right"], .dropdown-menu[x-placement^="bottom"], .dropdown-menu[x-placement^="left"] {
  right: auto;
  bottom: auto;
}

.dropdown-divider {
  height: 0;
  margin: 0.5rem 0;
  overflow: hidden;
  border-top: 1px solid #e9ecef;
}

.dropdown-item {
  display: block;
  width: 100%;
  padding: 0.25rem 1rem;
  clear: both;
  font-weight: 400;
  color: #212529;
  text-align: inherit;
  white-space: nowrap;
  background-color: transparent;
  border: 0;
}

.dropdown-item:hover, .dropdown-item:focus {
  color: #16181b;
  text-decoration: none;
  background-color: #f8f9fa;
}

.dropdown-item.active, .dropdown-item:active {
  color: #fff;
  text-decoration: none;
  background-color: #007bff;
}

.dropdown-item.disabled, .dropdown-item:disabled {
  color: #6c757d;
  pointer-events: none;
  background-color: transparent;
}

.dropdown-menu.show {
  display: block;
}

.dropdown-header {
  display: block;
  padding: 0.5rem 1rem;
  margin-bottom: 0;
  font-size: 0.875rem;
  color: #6c757d;
  white-space: nowrap;
}

.dropdown-item-text {
  display: block;
  padding: 0.25rem 1rem;
  color: #212529;
}

.btn-group,
.btn-group-vertical {
  position: relative;
  display: -ms-inline-flexbox;
  display: inline-flex;
  vertical-align: middle;
}

.btn-group > .btn,
.btn-group-vertical > .btn {
  position: relative;
  -ms-flex: 1 1 auto;
  flex: 1 1 auto;
}

.btn-group > .btn:hover,
.btn-group-vertical > .btn:hover {
  z-index: 1;
}

.btn-group > .btn:focus, .btn-group > .btn:active, .btn-group > .btn.active,
.btn-group-vertical > .btn:focus,
.btn-group-vertical > .btn:active,
.btn-group-vertical > .btn.active {
  z-index: 1;
}

.btn-toolbar {
  display: -ms-flexbox;
  display: flex;
  -ms-flex-wrap: wrap;
  flex-wrap: wrap;
  -ms-flex-pack: start;
  justify-content: flex-start;
}

.btn-toolbar .input-group {
  width: auto;
}

.btn-group > .btn:not(:first-child),
.btn-group > .btn-group:not(:first-child) {
  margin-left: -1px;
}

.btn-group > .btn:not(:last-child):not(.dropdown-toggle),
.btn-group > .btn-group:not(:last-child) > .btn {
  border-top-right-radius: 0;
  border-bottom-right-radius: 0;
}

.btn-group > .btn:not(:first-child),
.btn-group > .btn-group:not(:first-child) > .btn {
  border-top-left-radius: 0;
  border-bottom-left-radius: 0;
}

.dropdown-toggle-split {
  padding-right: 0.5625rem;
  padding-left: 0.5625rem;
}

.dropdown-toggle-split::after,
.dropup .dropdown-toggle-split::after,
.dropright .dropdown-toggle-split::after {
  margin-left: 0;
}

.dropleft .dropdown-toggle-split::before {
  margin-right: 0;
}

.btn-sm + .dropdown-toggle-split, .btn-group-sm > .btn + .dropdown-toggle-split {
  padding-right: 0.375rem;
  padding-left: 0.375rem;
}

.btn-lg + .dropdown-toggle-split, .btn-group-lg > .btn + .dropdown-toggle-split {
  padding-right: 0.75rem;
  padding-left: 0.75rem;
}

.btn-group.show .dropdown-toggle {
  box-shadow: none;
}

.btn-group.show .dropdown-toggle.btn-link {
  box-shadow: none;
}

.btn-group-vertical {
  -ms-flex-direction: column;
  flex-direction: column;
  -ms-flex-align: start;
  align-items: flex-start;
  -ms-flex-pack: center;
  justify-content: center;
}

.btn-group-vertical > .btn,
.btn-group-vertical > .btn-group {
  width: 100%;
}

.btn-group-vertical > .btn:not(:first-child),
.btn-group-vertical > .btn-group:not(:first-child) {
  margin-top: -1px;
}

.btn-group-vertical > .btn:not(:last-child):not(.dropdown-toggle),
.btn-group-vertical > .btn-group:not(:last-child) > .btn {
  border-bottom-right-radius: 0;
  border-bottom-left-radius: 0;
}

.btn-group-vertical > .btn:not(:first-child),
.btn-group-vertical > .btn-group:not(:first-child) > .btn {
  border-top-left-radius: 0;
  border-top-right-radius: 0;
}

.btn-group-toggle > .btn,
.btn-group-toggle > .btn-group > .btn {
  margin-bottom: 0;
}

.btn-group-toggle > .btn input[type="radio"],
.btn-group-toggle > .btn input[type="checkbox"],
.btn-group-toggle > .btn-group > .btn input[type="radio"],
.btn-group-toggle > .btn-group > .btn input[type="checkbox"] {
  position: absolute;
  clip: rect(0, 0, 0, 0);
  pointer-events: none;
}

.input-group {
  position: relative;
  display: -ms-flexbox;
  display: flex;
  -ms-flex-wrap: wrap;
  flex-wrap: wrap;
  -ms-flex-align: stretch;
  align-items: stretch;
  width: 100%;
}

.input-group > .form-control,
.input-group > .form-control-plaintext,
.input-group > .custom-select,
.input-group > .custom-file {
  position: relative;
  -ms-flex: 1 1 auto;
  flex: 1 1 auto;
  width: 1%;
  min-width: 0;
  margin-bottom: 0;
}

.input-group > .form-control + .form-control,
.input-group > .form-control + .custom-select,
.input-group > .form-control + .custom-file,
.input-group > .form-control-plaintext + .form-control,
.input-group > .form-control-plaintext + .custom-select,
.input-group > .form-control-plaintext + .custom-file,
.input-group > .custom-select + .form-control,
.input-group > .custom-select + .custom-select,
.input-group > .custom-select + .custom-file,
.input-group > .custom-file + .form-control,
.input-group > .custom-file + .custom-select,
.input-group > .custom-file + .custom-file {
  margin-left: -1px;
}

.input-group > .form-control:focus,
.input-group > .custom-select:focus,
.input-group > .custom-file .custom-file-input:focus ~ .custom-file-label {
  z-index: 3;
}

.input-group > .custom-file .custom-file-input:focus {
  z-index: 4;
}

.input-group > .form-control:not(:last-child),
.input-group > .custom-select:not(:last-child) {
  border-top-right-radius: 0;
  border-bottom-right-radius: 0;
}

.input-group > .form-control:not(:first-child),
.input-group > .custom-select:not(:first-child) {
  border-top-left-radius: 0;
  border-bottom-left-radius: 0;
}

.input-group > .custom-file {
  display: -ms-flexbox;
  display: flex;
  -ms-flex-align: center;
  align-items: center;
}

.input-group > .custom-file:not(:last-child) .custom-file-label,
.input-group > .custom-file:not(:last-child) .custom-file-label::after {
  border-top-right-radius: 0;
  border-bottom-right-radius: 0;
}

.input-group > .custom-file:not(:first-child) .custom-file-label {
  border-top-left-radius: 0;
  border-bottom-left-radius: 0;
}

.input-group-prepend,
.input-group-append {
  display: -ms-flexbox;
  display: flex;
}

.input-group-prepend .btn,
.input-group-append .btn {
  position: relative;
  z-index: 2;
}

.input-group-prepend .btn:focus,
.input-group-append .btn:focus {
  z-index: 3;
}

.input-group-prepend .btn + .btn,
.input-group-prepend .btn + .input-group-text,
.input-group-prepend .input-group-text + .input-group-text,
.input-group-prepend .input-group-text + .btn,
.input-group-append .btn + .btn,
.input-group-append .btn + .input-group-text,
.input-group-append .input-group-text + .input-group-text,
.input-group-append .input-group-text + .btn {
  margin-left: -1px;
}

.input-group-prepend {
  margin-right: -1px;
}

.input-group-append {
  margin-left: -1px;
}

.input-group-text {
  display: -ms-flexbox;
  display: flex;
  -ms-flex-align: center;
  align-items: center;
  padding: 0.375rem 0.75rem;
  margin-bottom: 0;
  font-size: 1rem;
  font-weight: 400;
  line-height: 1.5;
  color: #495057;
  text-align: center;
  white-space: nowrap;
  background-color: #e9ecef;
  border: 1px solid #ced4da;
  border-radius: 0.25rem;
}

.input-group-text input[type="radio"],
.input-group-text input[type="checkbox"] {
  margin-top: 0;
}

.input-group-lg > .form-control:not(textarea),
.input-group-lg > .custom-select {
  height: calc(2.875rem + 2px);
}

.input-group-lg > .form-control,
.input-group-lg > .custom-select,
.input-group-lg > .input-group-prepend > .input-group-text,
.input-group-lg > .input-group-append > .input-group-text,
.input-group-lg > .input-group-prepend > .btn,
.input-group-lg > .input-group-append > .btn {
  padding: 0.5rem 1rem;
  font-size: 1.25rem;
  line-height: 1.5;
  border-radius: 0.3rem;
}

.input-group-sm > .form-control:not(textarea),
.input-group-sm > .custom-select {
  height: calc(1.8125rem + 2px);
}

.input-group-sm > .form-control,
.input-group-sm > .custom-select,
.input-group-sm > .input-group-prepend > .input-group-text,
.input-group-sm > .input-group-append > .input-group-text,
.input-group-sm > .input-group-prepend > .btn,
.input-group-sm > .input-group-append > .btn {
  padding: 0.25rem 0.5rem;
  font-size: 0.875rem;
  line-height: 1.5;
  border-radius: 0.2rem;
}

.input-group-lg > .custom-select,
.input-group-sm > .custom-select {
  padding-right: 1.75rem;
}

.input-group > .input-group-prepend > .btn,
.input-group > .input-group-prepend > .input-group-text,
.input-group > .input-group-append:not(:last-child) > .btn,
.input-group > .input-group-append:not(:last-child) > .input-group-text,
.input-group > .input-group-append:last-child > .btn:not(:last-child):not(.dropdown-toggle),
.input-group > .input-group-append:last-child > .input-group-text:not(:last-child) {
  border-top-right-radius: 0;
  border-bottom-right-radius: 0;
}

.input-group > .input-group-append > .btn,
.input-group > .input-group-append > .input-group-text,
.input-group > .input-group-prepend:not(:first-child) > .btn,
.input-group > .input-group-prepend:not(:first-child) > .input-group-text,
.input-group > .input-group-prepend:first-child > .btn:not(:first-child),
.input-group > .input-group-prepend:first-child > .input-group-text:not(:first-child) {
  border-top-left-radius: 0;
  border-bottom-left-radius: 0;
}

.custom-control {
  position: relative;
  z-index: 1;
  display: block;
  min-height: 1.5rem;
  padding-left: 1.5rem;
  -webkit-print-color-adjust: exact;
  color-adjust: exact;
}

.custom-control-inline {
  display: -ms-inline-flexbox;
  display: inline-flex;
  margin-right: 1rem;
}

.custom-control-input {
  position: absolute;
  left: 0;
  z-index: -1;
  width: 1rem;
  height: 1.25rem;
  opacity: 0;
}

.custom-control-input:checked ~ .custom-control-label::before {
  color: #fff;
  border-color: #007bff;
  background-color: #007bff;
  box-shadow: none;
}

.custom-control-input:focus ~ .custom-control-label::before {
  box-shadow: inset 0 0 0 rgba(0, 0, 0, 0), 0 0 0 1px #fff, 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.custom-control-input:focus:not(:checked) ~ .custom-control-label::before {
  border-color: #80bdff;
}

.custom-control-input:not(:disabled):active ~ .custom-control-label::before {
  color: #fff;
  background-color: #b3d7ff;
  border-color: #b3d7ff;
  box-shadow: none;
}

.custom-control-input[disabled] ~ .custom-control-label, .custom-control-input:disabled ~ .custom-control-label {
  color: #6c757d;
}

.custom-control-input[disabled] ~ .custom-control-label::before, .custom-control-input:disabled ~ .custom-control-label::before {
  background-color: #e9ecef;
}

.custom-control-label {
  position: relative;
  margin-bottom: 0;
  vertical-align: top;
}

.custom-control-label::before {
  position: absolute;
  top: 0.25rem;
  left: -1.5rem;
  display: block;
  width: 1rem;
  height: 1rem;
  pointer-events: none;
  content: "";
  background-color: #dee2e6;
  border: #adb5bd solid 1px;
  box-shadow: inset 0 0.25rem 0.25rem rgba(0, 0, 0, 0.1);
}

.custom-control-label::after {
  position: absolute;
  top: 0.25rem;
  left: -1.5rem;
  display: block;
  width: 1rem;
  height: 1rem;
  content: "";
  background: no-repeat 50% / 50% 50%;
}

.custom-checkbox .custom-control-label::before {
  border-radius: 0.25rem;
}

.custom-checkbox .custom-control-input:checked ~ .custom-control-label::after {
  background-image: url("data:image/svg+xml;charset=utf8,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3E%3Cpath fill='%23fff' d='M6.564.75l-3.59 3.612-1.538-1.55L0 4.26 2.974 7.25 8 2.193z'/%3E%3C/svg%3E");
}

.custom-checkbox .custom-control-input:indeterminate ~ .custom-control-label::before {
  border-color: #007bff;
  background-color: #007bff;
  box-shadow: none;
}

.custom-checkbox .custom-control-input:indeterminate ~ .custom-control-label::after {
  background-image: url("data:image/svg+xml;charset=utf8,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 4 4'%3E%3Cpath stroke='%23fff' d='M0 2h4'/%3E%3C/svg%3E");
}

.custom-checkbox .custom-control-input:disabled:checked ~ .custom-control-label::before {
  background-color: rgba(0, 123, 255, 0.5);
}

.custom-checkbox .custom-control-input:disabled:indeterminate ~ .custom-control-label::before {
  background-color: rgba(0, 123, 255, 0.5);
}

.custom-radio .custom-control-label::before {
  border-radius: 50%;
}

.custom-radio .custom-control-input:checked ~ .custom-control-label::after {
  background-image: url("data:image/svg+xml;charset=utf8,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3E%3Ccircle r='3' fill='%23fff'/%3E%3C/svg%3E");
}

.custom-radio .custom-control-input:disabled:checked ~ .custom-control-label::before {
  background-color: rgba(0, 123, 255, 0.5);
}

.custom-switch {
  padding-left: 2.25rem;
}

.custom-switch .custom-control-label::before {
  left: -2.25rem;
  width: 1.75rem;
  pointer-events: all;
  border-radius: 0.5rem;
}

.custom-switch .custom-control-label::after {
  top: calc(0.25rem + 2px);
  left: calc(-2.25rem + 2px);
  width: calc(1rem - 4px);
  height: calc(1rem - 4px);
  background-color: #adb5bd;
  border-radius: 0.5rem;
  transition: transform 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

@media (prefers-reduced-motion: reduce) {
  .custom-switch .custom-control-label::after {
    transition: none;
  }
}

.custom-switch .custom-control-input:checked ~ .custom-control-label::after {
  background-color: #dee2e6;
  transform: translateX(0.75rem);
}

.custom-switch .custom-control-input:disabled:checked ~ .custom-control-label::before {
  background-color: rgba(0, 123, 255, 0.5);
}

.custom-select {
  display: inline-block;
  width: 100%;
  height: calc(2.25rem + 2px);
  padding: 0.375rem 1.75rem 0.375rem 0.75rem;
  font-size: 1rem;
  font-weight: 400;
  line-height: 1.5;
  color: #495057;
  vertical-align: middle;
  background: #fff url("data:image/svg+xml;charset=utf8,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 4 5'%3E%3Cpath fill='%23343a40' d='M2 0L0 2h4zm0 5L0 3h4z'/%3E%3C/svg%3E") no-repeat right 0.75rem center/8px 10px;
  border: 1px solid #ced4da;
  border-radius: 0.25rem;
  box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.075);
  -webkit-appearance: none;
  -moz-appearance: none;
  appearance: none;
}

.custom-select:focus {
  border-color: #80bdff;
  outline: 0;
  box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.075);
}

.custom-select:focus::-ms-value {
  color: #495057;
  background-color: #fff;
}

.custom-select[multiple], .custom-select[size]:not([size="1"]) {
  height: auto;
  padding-right: 0.75rem;
  background-image: none;
}

.custom-select:disabled {
  color: #6c757d;
  background-color: #e9ecef;
}

.custom-select::-ms-expand {
  display: none;
}

.custom-select:-moz-focusring {
  color: transparent;
  text-shadow: 0 0 0 #495057;
}
 
/*# sourceMappingURL=adminlte.css.map */
 </style>
   
 </head>

<body>
    <!-- <div class="container-fluid">
        <table class="table">
            <div class="container text-center">
                <div class="row">
                    <div class="col">
                        <img width="150px" src="{{ asset('img/santaniello.png') }}" class="img-fluid" alt="logo"></td>
                    </div>
                    <div class="col-6">
                        <b>Sistema di Gestione Integrato</b>
                        <p>{{$title}} </p>
                    </div>
                    <div class="col">
                        <b>{{$code}}</b>
                        <p>Rev {{$review}} del {{ \Carbon\Carbon::now()->format('d-m-Y') }}</p>
                    </div>
                </div>
            </div>
    </div>  -->