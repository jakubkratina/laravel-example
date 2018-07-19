# Laravel example

Hi,

this code is a snippet of my canvas editor with React at frontend and Laravel API at backend. In this app of mine, you have a canvas where you can add "components" to. One type of a component is the *image component*. The image can be a raster image (png, jpg,...) or a photoshop file (psd). In this example, you can find how I handle this task.

As you can see, there are two directories: `app` and `tests`. Start at `app/Http/Controllers/GalleryController` and follow the `store` method.

The model part of this app is inspired by `Laravel Spark` by the Taylor Otwell. Why I've decided to use this version of *Command bus handler* is described extensively here: [https://www.youtube.com/watch?v=WubTcL7wILI](https://www.youtube.com/watch?v=WubTcL7wILI (Czech)).

If you have any question, please feel free to contact me at *me@jakubkratina.cz*.

Thank you.

Jakub
