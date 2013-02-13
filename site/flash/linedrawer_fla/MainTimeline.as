package linedrawer_fla
{
    import com.adobe.images.*;
    import flash.display.*;
    import flash.events.*;
    import flash.net.*;
    import flash.text.*;
    import flash.utils.*;
	import flash.external.ExternalInterface;
 
    dynamic public class MainTimeline extends MovieClip
    {
        public var container:Sprite;
        public var minX:int;
        public var statusmsg:TextField;
        public var printDiff:int;
        public var maxY:int;
        public var saveButton:SimpleButton;

        public function MainTimeline()
        {
            addFrameScript(0, this.frame1);
            return;
        }// end function

        public function onMouseDownhandler(event:Event) : void
        {
            if (mouseX > this.minX && mouseY < this.maxY)
            {
                this.container.graphics.moveTo(mouseX, mouseY);
                stage.addEventListener(MouseEvent.MOUSE_MOVE, this.onMouseMovehandler);
            }
            return;
        }// end function

        function frame1()
        {
            this.container = new Sprite();
            this.maxY = 200;
            this.minX = 50;
            this.printDiff = 75;
            addChild(this.container);
            this.container.graphics.lineStyle(1, 0, 100, false);
            stage.addEventListener(MouseEvent.MOUSE_DOWN, this.onMouseDownhandler);
            stage.addEventListener(MouseEvent.MOUSE_UP, this.onMouseUphandler);
            this.saveButton.addEventListener(MouseEvent.CLICK, this.onSaveHandler);
            return;
        }// end function

        public function onSaveHandler(event:Event) : void
        {
            var loader:URLLoader;
            var event:* = event;
            var caseid:* = this.loaderInfo.parameters["caseid"];
			var cid:* = this.loaderInfo.parameters["cid"];
			var aType:* = this.loaderInfo.parameters["atype"];
			var depart:* = this.loaderInfo.parameters["depart"];
			var hostUrl:* = this.loaderInfo.parameters["hostUrl"];
			var activityId:* = this.loaderInfo.parameters["id"];
            
            
            var jpgSource:* = new BitmapData(stage.width, stage.height - this.printDiff);
            jpgSource.draw(stage);
            var jpgEncoder:* = new JPGEncoder();
            var jpgStream:* = jpgEncoder.encode(jpgSource);
            var header:* = new URLRequestHeader("Content-type", "application/octet-stream");
            var jpgURLRequest:* = new URLRequest("http://"+hostUrl+"/jpeg_encoder.php?aType="+aType+"&caseid=" + caseid+"&cid="+cid+"&depart="+depart+"&id="+activityId);
            jpgURLRequest.requestHeaders.push(header);
            jpgURLRequest.method = URLRequestMethod.POST;
            jpgURLRequest.data = jpgStream;
            try
            {
                loader = new URLLoader();
                loader.addEventListener(Event.COMPLETE, this.completeHandler);
                loader.load(jpgURLRequest);
            }
            catch (e:Error)
            {
            }
            return;
        }// end function

        public function onMouseUphandler(event:Event) : void
        {
            stage.removeEventListener(MouseEvent.MOUSE_MOVE, this.onMouseMovehandler);
            return;
        }// end function

        public function completeHandler(event:Event) : void
        {
            var _loc_2:* = URLLoader(event.target);
            this.statusmsg.text = "Signature successfully saved!";
			ExternalInterface.call("popupSuccess");
            return;
        }// end function

        public function onMouseMovehandler(event:Event) : void
        {
            this.container.graphics.lineTo(mouseX, mouseY);
            return;
        }// end function

    }
}
