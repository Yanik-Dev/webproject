<?php 
 $title = "Business-List";
 include '../../includes/admin-layout.php';
?>
   <div class="ui container">
        <div class="ui centered padded grid">
            <div class="eight column" >
              <button class="ui teal labeled icon button" id="add-business">
                <span>Add New Business</span> 
                <i class="add icon"></i>
              </button>
            </div>
        </div>
       <div class="ui centered padded grid " id="businesses">

        </div>
    </div>
</div>
<!-- close grid -->

<!-- business form modal -->
<div class="ui medium business modal">
  <div class=" content">
  <button class="mini ui icon button circular close-modal-btn"  style="position: absolute; top:3px;right: 3px;" data-id="{{id}}">
      <i class="large close icon"></i> 
  </button>
  <div class="ui negative message hide-element" id="error-msg"></div>
    <form action="" class="ui form">
    <h4 class="ui dividing header"><i class="building icon"></i> Business Information</h4>
    <div class="sixteen wide field">
      <label>Business Name</label>
        <input type="text" name="name" id="name" placeholder="">
        <span class="ui hide-element" style="color: red" id="exist-msg"><i class="close icon"></i>Business already exist.</span>
      </div>
    <div class="sixteen wide field">
        <label>Business Description</label>
        <textarea rows="2" maxlength="255" name="description" class="description-textarea" id="description"></textarea>
        <div id="textarea-feedback"><span class="remaining">255</span>/255</div>
    </div>
    <h4 class="ui dividing header"><i class="pin icon"></i> Location Information</h4>
    <div class="field">
        <div class="fields">
          <div class="eight wide field">
        <label>Street/District</label>
            <input type="text" name="street" id="street" placeholder="Street/District">
          </div>
          <div class="six wide field">
            <label>City/Town</label>
            <input type="text" name="city" id="city" placeholder="City/Town">
          </div>
          <div class="six wide field">
          <label>Province</label>
          <input type="text" name="province" id="province" placeholder="Province">
          </div>
        </div>
    </div>
    <h4 class="ui dividing header"><i class="phone icon"></i> Contact Information</h4>
    <div class="field">
        <div class="fields">
          <div class="eight wide field">
            <label>Contact Number 1</label>
            <input type="text"name="mobile" id="mobile" placeholder="(876) 000-0000">
          </div>
          <div class="eight wide field">
            <label>Contact Number 2</label>
            <input type="text" name="telephone" id="telephone" placeholder="(876) 000-0000">
          </div>
        </div>
    </div>
    <div class="two fields">
      <div class="field">
        <label>Email</label>
        <input type="text" name="email" id="email" placeholder="example@here.com">
        </select>
      </div>
      <div class="field">
        <label>Website</label>
        <input type="text" name="website" id="website" placeholder="http://www.example.com">
        </select>
      </div>
    </div>
    <input type="hidden" name="token" value="<?=SecurityService::generateToken("crsf_token");?>">
    <input type="hidden" name="id" id="id" >
    </form>
  </div>
  <div class="actions">
    <div class="ui button close-modal-btn" >Cancel</div>
    <button class="ui button" id="business-submit-btn">OK</button>
  </div>
</div>
<!-- ./business form modal -->


<!-- confirmation Modal -->
<div class="ui basic confirm modal">
  <div class="ui icon header">
    <i class="window close outline icon"></i>
    All items relating to this business will also be deleted. <br />
    Are you sure you want to perform this action?
  </div>
  <div class="content">
    <p></p>
  </div>
  <div class="actions">
    <div class="ui red  inverted button deny">
      <i class="remove icon"></i>
      No
    </div>
    <div class="ui green basic inverted button approve">
      <i class="checkmark icon"></i>
      Yes
    </div>
  </div>
</div>
<!-- ./confirmation modal -->

<!-- description modal -->
<div class="ui tiny description modal">
  <div class="header"></div>
  <div class="scrolling content">
    <div class="meta">
        <span><h3><i class="info icon"></i> Description</h3></span>
    </div>
    <p class="description"></p>

    <div class="meta">
        <span><h3><i class="phone icon"></i> Contact Information</h3></span>
    </div>

    <img src="" alt="" class="contact-qrcode" align="left">
    </br />
    <span class="mobile"></span>
    <span class="telephone"></span>
    <span class="email"></span> 
    <span class="website"></span>
    
  </div>
</div>
<!-- ./description modal -->

<!-- business item template -->
<script type="text/template" id="business-template">
<div class="sixteen wide mobile eight wide tablet eight wide computer column segment-cell business-item-{{id}}" >
    <div class="ui raised segment">
        <a class="ui business-name" style="color: #3d3d3d; font-size:16px; font-weight:bold"><span class="name">{{name}}</span></a>
        <div class="ui items">
            <div class="item">
                <div class="ui small image">
                    <img src="{{logo}}">
                    <div class="mini ui icon button circular" style="position: absolute; top:5px;right: 3px;">
                        <i class="large upload icon"></i> 
                        <input class="file-input"  type="file" name="file" >
                    </div>
                </div>
                <div class="content">
                  <div class="meta">
                      <span>Description</span>
                  </div>
                  <div>
                      <span class="description-limited">{{limitText}}</span> <br />
                      <span class="street">{{street}}</span>,  <span class="city">{{city}}</span>,  <span class="province">{{province}}</span><br />
                      <div style="display:none">
                      <span class="description">{{description}}</span> <br />
                      <span class="mobile">{{mobile}}</span>
                      <span class="telephone">{{telephone}}</span><br />
                      <span class="email">{{email}}</span> <br />
                      <span class="website">{{website}}</span>
                      <span class="qrcode">{{contactQrCode}}</span>
                      </div>
                  </div> 
                  <div class="extra">
                      <button class="mini ui icon labeled  button circular  editBtn"  data-id="{{id}}"><i class="large edit icon"></i> Edit</button>
                      <button class="mini ui icon labeled  button circular viewMore"  data-id="{{id}}">
                      <i class="large list icon"></i> Offerings
                    </button>
                    <button class="mini ui icon labeled  button circular viewMore"  data-id="{{id}}">
                        <i class="large close icon"></i> More
                      </button>
                  </div>
                  <button class="mini ui icon button circular deleteBtn"  style="background: transparent; position: absolute; top:8px;right: 8px;" data-id="{{id}}">
                  <i class="large close icon"></i> 
                  </button>
                </div>
            </div>
        </div>
        <div class="ui uploading inverted dimmer">
          <div class="ui indeterminate text loader">Uploading</div>
        </div>
        <div class="ui mini bottom attached error upload message hide-element">
          <i class="close icon"></i>
          <span></span>
        </div>
    </div> 
</div> 
</script>
<!-- ./business item template -->
<script src="./../assets/js/app.module.js"></script>
<script src="./../assets/js/business.module.js"></script>


