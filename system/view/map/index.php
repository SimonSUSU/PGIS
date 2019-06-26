<? $this->load_view('header',array('area_id'=>$rs['area_id'])); ?>
<div class="mapName"><?=$rs['name']?>3D可视化管控图</div>
<div id="container"></div>

<? $this->load_view('footer'); ?>
<script language="javascript" src="//webapi.amap.com/maps?v=1.4.9&key=ebd77d08ae12619495fca064ce5c2296&plugin=Map3D"></script>
<script language="javascript">
function mapInit(){
    // 创建地图实例

    var map = new AMap.Map("container",{
        viewMode:'3D',//开启3D视图,默认为关闭
        pitch: 60,// 地图俯仰角度，有效范围0度~83度
        //rotation: 90,
        zoom: 18,//初始地图级别
        center: [<?=$rs['lnglat']?>], //初始地图中心点
        resizeEnable: false, //是否监控地图容器尺寸变化
        //mapStyle: 'amap://styles/macaron',
        mapStyle: 'amap://styles/796ce70304e2d57eb38d107d8fb69a52', //加载自定义样式
        //mapStyle: 'amap://styles/midnight',
        //mapStyle: 'amap://styles/a34823489d0167e5c9b9a0eeefcc4c96',
        
        showIndoorMap: false,//关闭室内地图
        showBuildingBlock:true,
    });

    //种类要素显示(bg区域面, point标注,road道路及道路标注,building建筑物)
    map.setFeatures(['bg','road','building']);    

    // 同时引入工具条插件，比例尺插件和鹰眼插件
    AMap.plugin([
        'AMap.ControlBar',//添加3D罗盘控制
        //'AMap.ToolBar',     //添加工具条控件
        //'AMap.Scale',   //添加比例尺控件
       // 'AMap.OverView',    //添加鹰眼控件
       // 'AMap.MapType', //在图面添加类别切换控件
        //'AMap.Geolocation'  //在图面添加定位控件
        ], function(){       
            // 添加3D罗盘控制,组合了旋转、倾斜、复位、缩放在内的地图控件，在3D地图模式下会显示
            map.addControl(new AMap.ControlBar());

            // 在图面添加工具条控件，工具条控件集成了缩放、平移、定位等功能按钮在内的组合控件
            //map.addControl(new AMap.ToolBar());

            // 在图面添加比例尺控件，展示地图在当前层级和纬度下的比例尺
            //map.addControl(new AMap.Scale({position:'RB'}));

            // 在图面添加鹰眼控件，在地图右下角显示地图的缩略图
            //map.addControl(new AMap.OverView({isOpen:true}));

            // 在图面添加类别切换控件，实现默认图层与卫星图、实施交通图层之间切换的控制
           // map.addControl(new AMap.MapType());

            // 在图面添加定位控件，用来获取和展示用户主机所在的经纬度位置
            //map.addControl(new AMap.Geolocation());
    });



    var circle = new AMap.Circle({
        center: new AMap.LngLat(<?=$rs['gltf_lnglat']?>),// 圆心位置
        radius: 3000, //圆半径，单位:米
        fillColor: '#ff0000',   // 圆形填充颜色,使用16进制颜色代码赋值。默认值为#006600
        fillOpacity:0, //圆形填充透明度，取值范围[0,1]，0表示完全透明，1表示不透明。默认为0.9

        strokeColor: '#ff0000', // 描边颜色 使用16进制颜色代码赋值 默认值为#006600
        strokeOpacity:1, //轮廓线透明度，取值范围[0,1]，0表示完全透明，1表示不透明。默认为0.9
        strokeWeight: 1, // 描边宽度、轮廓线宽度
        strokeStyle:'dashed', //轮廓线样式，实线:solid，虚线:dashed
    });
    circle.setMap(map);   



    // 创建Object3DLayer图层
    var object3Dlayer = new AMap.Object3DLayer();
    map.add(object3Dlayer);
    
    map.plugin(["AMap.GltfLoader"],function(){
        var urlCity = '<?=$rs['gltf_file']?>';
        var paramCity = {
            position: new AMap.LngLat(<?=$rs['gltf_lnglat']?>), // 必须
            scale: <?=$rs['gltf_scale']?>, // 非必须，默认1, 设置模型缩放倍数
            height: <?=$rs['gltf_height']?>,  // 非必须，默认0, 设置模型高度
            scene: <?=$rs['gltf_scene']?>, // 非必须，默认0    ,设置当前场景序号
        } 
        var gltfObj = new AMap.GltfLoader();
        gltfObj.load(urlCity, function(gltfCity){
            gltfCity.setOption(paramCity);
            gltfCity.rotateX(<?=$rs['gltf_rotateX']?>);
            gltfCity.rotateY(<?=$rs['gltf_rotateY']?>);
            gltfCity.rotateZ(<?=$rs['gltf_rotateZ']?>);
            object3Dlayer.add(gltfCity);
        });
    });
}
</script>